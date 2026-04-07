<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{public function index(Request $request)
{
    $query = Client::query();

    if ($search = $request->get('search')) {
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('telephone', 'like', "%{$search}%");
        });
    }

    // جلب كل الـClients مرة وحدة
    $clients = $query->orderBy('id', 'desc')->get();

    return view('clients.index', compact('clients'));
}

    // عرض الفورم باش تزيد Client جديد
    public function create()
    {
        return view('clients.create');
    }

    // تخزين Client جديد
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'nom' => 'required|string|max:255',
            'date' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        // إنشاء Client
        Client::create($request->all());

        // Redirect مع رسالة نجاح
        return redirect()->route('clients.index')->with('success', 'Client créé avec succès');
    }

    // عرض تفاصيل Client واحد
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    // عرض الفورم لتعديل Client
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // تحديث Client
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'date' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Client mis à jour');
    }

    // حذف Client
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client supprimé');
    }

    // Duplicate client: prefill create form
    public function duplicate(Client $client)
    {
        $duplicateData = [
            'nom' => $client->nom,
            'date' => $client->date,
            'telephone' => $client->telephone,
            'email' => $client->email,
            'adresse' => $client->adresse,
        ];

        return redirect()->route('clients.create')
            ->with('duplicateData', $duplicateData)
            ->with('message', 'Client dupliqué — modifiez les champs puis enregistrez.');
    }
    
    public function situation()
{
    $clients = \App\Models\Client::with(['factures.paiements'])->get();

    return view('clients.situation', compact('clients'));
}
    public function reste()
{
    $clients = \App\Models\Client::with(['devis.paiements'])->get();

    return view('clients.reste', compact('clients'));
}
}
