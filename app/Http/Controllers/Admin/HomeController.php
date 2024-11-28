<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected ScheduleService $scheduleService;

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
     * Display the list of orders formatted as calendar events.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $timezone = 'America/Toronto';

        // Retrieve orders with their related data
        $orders = Order::with(['orderItems.product.productType', 'client'])
            ->orderBy('deadline', 'asc')
            ->get();

        // Calculate total products and order count
        $totalProducts = $orders->sum(fn($order) => $order->orderItems->count());
        $orderCount = $orders->count();
        $averageProductsPerOrder = $orderCount > 0 ? round($totalProducts / $orderCount, 2) : 0;

        // Initialize events and state variables
        $events = [];
        $lastEndTime = Carbon::now()->setTimezone($timezone)->startOfDay()->setHour(8);
        $lastProductType = null;

        foreach ($orders as $order) {
            // Retrieve order's product type and first item for convenience
            $productType = $order->orderItems->first()->product->productType;
            $productTypeId = $productType->id;
            $productTypeName = $productType->name;
            $orderItems = $order->orderItems;

            // Calculate the production duration for the order
            $productionDuration = $this->scheduleService->calculateProductionDuration($orderItems);

            // Determine start time, adjusted for overlap with previous order
            $start = $this->scheduleService->getStartTime($lastEndTime, $timezone);

            // Handle changeover time if product type has changed
            $changeoverTime = 0;
            if ($lastProductType && $lastProductType !== $productTypeId) {
                $changeoverTime = $this->scheduleService->getChangeoverTime($lastProductType, $productTypeId);
                $productionDuration += $changeoverTime;
            }

            // Calculate the end time based on the adjusted start time and production duration
            $end = $this->scheduleService->getEndTime($start, $productionDuration);

            // Add event to the events array
            $events[] = $this->formatEventData($order, $start, $end, $changeoverTime, $productTypeName, $orderItems, $productionDuration);
            // Update state for next iteration
            $lastEndTime = $end;
            $lastProductType = $productTypeId;
        }

        return view('admin.home.index', compact('events', 'averageProductsPerOrder'));
    }

    /**
     * Format event data for calendar.
     *
     * @param Order $order
     * @param Carbon $start
     * @param Carbon $end
     * @param int $changeoverTime
     * @param string $productTypeName
     * @param \Illuminate\Database\Eloquent\Collection $orderItems
     * @param int $productionDuration
     * @return array
     */
    private function formatEventData(Order $order, Carbon $start, Carbon $end, int $changeoverTime, string $productTypeName, $orderItems, int $productionDuration): array
    {
        $url = route('admin.order.edit', $order->id);
        $backgroundColor = $this->scheduleService->getColorForProductType($productTypeName ?? 'default');
        $productList = $orderItems->map(function ($item) {
            return $item->product->name . ' (x' . $item->quantity . ')';
        })->implode(', ');

        return [
            'title' => $order->client->name,
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
            'url' => $url,
            'backgroundColor' => $backgroundColor,
            'borderColor' => $backgroundColor,
            'order' => $order,
            'changeOver' => $changeoverTime,
            'productList' => $productList,
            'type' => $productTypeName,
            'productionDuration' => $productionDuration
        ];
    }
}
