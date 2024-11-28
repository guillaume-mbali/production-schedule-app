<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Client;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ScheduleService;
use App\Models\ProductType;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $scheduleService;

    /**
     * Inject the ScheduleService into the controller.
     *
     * @param ScheduleService $scheduleService
     */
    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display the list of orders, sorted by deadline.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $orders = Order::with(['orderItems.product.productType'])
            ->orderBy('deadline', 'asc')
            ->get();

        return view('admin.order.index', compact('orders'));
    }

    /**
     * Reset session data and redirect to step 1 of the order creation process.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->session()->forget('productionSchedules');
        return redirect()->route('admin.order.stepper.step1');
    }

    /**
     * Show the form for step 1 of creating an order.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function createStepOne(Request $request): \Illuminate\View\View
    {
        $clients = Client::all();
        $products = Product::all();
        $productTypes = ProductType::all();

        return view('admin.order.stepper.step1', compact('clients', 'products', 'productTypes'));
    }

    /**
     * Handle the POST request for step 1, validate data and save to session.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateStepOne(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'deadline' => 'required|date|after:today',
            'product_data' => 'required|json',
        ]);

        $order = new Order([
            'client_id' => $validatedData['client_id'],
            'deadline' => $validatedData['deadline'],
            'status' => 'pending',
        ]);
        $request->session()->put('order', $order);

        $productData = json_decode($validatedData['product_data'], true);
        $orderItems = collect($productData)->map(function ($item) {
            return new OrderItem([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => (int) $item['total_price'],
            ]);
        });
        $request->session()->put('order_items', $orderItems);

        return redirect()->route('admin.order.stepper.step2');
    }

    /**
     * Show the form for step 2 of creating an order.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function createStepTwo(Request $request): \Illuminate\View\View
    {
        $order = $request->session()->get('order');
        $orderItems = $request->session()->get('order_items');
        $products = Product::whereIn('id', $orderItems->pluck('product_id'))->get();

        return view('admin.order.stepper.step2', compact('order', 'orderItems', 'products'));
    }

    /**
     * Handle the POST request for step 2, save order and order items, then clear session data.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateStepTwo(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = $request->session()->get('order');
        if (!$order) {
            return redirect()->route('admin.order.stepper.step1');
        }

        $order->save();

        $orderItems = $request->session()->get('order_items');
        foreach ($orderItems as $orderItem) {
            $order->orderItems()->save($orderItem);
        }

        $request->session()->forget(['order', 'order_items']);

        return redirect()->route('admin.order.index');
    }

    /**
     * Delete the specified order.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Show the form to edit an existing order.
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id): \Illuminate\View\View
    {
        $order = Order::with(['orderItems.product.productType', 'client'])->findOrFail($id);

        // Calculate production durations for each order item
        $orderItemsWithDurations = $order->orderItems->map(function ($orderItem) use ($order) {
            $productionDuration = $this->scheduleService->calculateProductionDuration($order->orderItems); // Pass the entire orderItems collection

            return [
                'product_name' => $orderItem->product->name,
                'quantity' => $orderItem->quantity,
                'production_duration' => $productionDuration . ' minutes',
            ];
        });

        $clients = Client::all();
        $products = Product::all();
        $productTypes = ProductType::all();

        return view('admin.order.edit', compact('order', 'clients', 'products', 'productTypes', 'orderItemsWithDurations'));
    }



    /**
     * Update the specified order in the database.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validatedData = $request->validate([
            'deadline' => 'required|date|after:today',
        ]);

        $order->deadline = $validatedData['deadline'];
        $order->save();

        return redirect()->route('admin.order.index')->with('success', 'La commande a été mise à jour avec succès.');
    }
}
