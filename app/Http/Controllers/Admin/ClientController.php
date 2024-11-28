<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a list of all clients.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all clients
        $clients = Client::all();

        // Return the view with the list of clients
        return view('admin.client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Create a new Client instance
        $client = new Client();

        // Return the view for creating a new client, passing the empty client instance
        return view('admin.client.create', compact('client'));
    }

    /**
     * Store a newly created client in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'email' => 'required|email|unique:clients',
            'phone_number' => 'required|string|max:20',
        ]);

        // Create a new client record using the validated data
        Client::create($validated);

        // Redirect back to the clients list with a success message
        return redirect()->route('admin.client.index')->with('success', 'Client créé avec succès.');
    }

    /**
     * Display the specified client.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\View\View
     */
    public function show(Client $client)
    {
        // Return the view for showing the client details
        return view('admin.client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\View\View
     */
    public function edit(Client $client)
    {
        // Return the view for editing the client, passing the client data
        return view('admin.client.create', compact('client'));
    }

    /**
     * Update the specified client in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Client $client)
    {
        // Validate the incoming request data, allowing the email to remain the same for the client being updated
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone_number' => 'required|string|max:20',
        ]);

        // Update the client record with the validated data
        $client->update($validated);

        // Redirect back to the clients list with a success message
        return redirect()->route('admin.client.index')->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified client from storage.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Client $client)
    {
        // Delete the client record
        $client->delete();

        // Redirect back to the clients list with a success message
        return redirect()->route('admin.client.index')->with('success', 'Client supprimé avec succès.');
    }
}
