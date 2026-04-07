<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    // LISTE
    public function index(Request $request)
    {
        $query = Fournisseur::query();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('adresse', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%");
            });
        }

          $fournisseurs = $query->orderBy('id', 'desc')->get();
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    // FORM CREATE
    public function create()
    {
        return view('fournisseurs.create');
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'ice' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        // if date not provided set today
        if (empty($validated['date'])) {
            $validated['date'] = now()->format('Y-m-d');
        }

        Fournisseur::create($validated);

        return redirect()
            ->route('fournisseurs.index')
            ->with('success','Fournisseur créé avec succès');
    }

    // SHOW
    public function show(Fournisseur $fournisseur)
    {
        return view('fournisseurs.show', compact('fournisseur'));
    }

    // EDIT
    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    // UPDATE
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'ice' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        // if date omitted preserve existing or default to today
        if (empty($validated['date'])) {
            $validated['date'] = $fournisseur->date ?? now()->format('Y-m-d');
        }

        $fournisseur->update($validated);

        return redirect()
            ->route('fournisseurs.index')
            ->with('success','Fournisseur modifié avec succès');
    }

    // DELETE
    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()
            ->route('fournisseurs.index')
            ->with('success','Fournisseur supprimé avec succès');
    }

    // Duplicate fournisseur: prefill create form
    public function duplicate(Fournisseur $fournisseur)
    {
        $duplicateData = [
            'nom' => $fournisseur->nom,
            'telephone' => $fournisseur->telephone,
            'email' => $fournisseur->email,
            'adresse' => $fournisseur->adresse,
            'ville' => $fournisseur->ville,
            'ice' => $fournisseur->ice,
            'date' => $fournisseur->date ?? $fournisseur->created_at->format('Y-m-d'),
        ];

        return redirect()->route('fournisseurs.create')
            ->with('duplicateData', $duplicateData)
            ->with('message', 'Fournisseur dupliqué — modifiez les champs puis enregistrez.');
    }
}