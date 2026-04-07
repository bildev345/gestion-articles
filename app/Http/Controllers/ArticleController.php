<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('fournisseur');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('designation', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhereHas('fournisseur', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

         $articles = $query->orderBy('id', 'desc')->get();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::all();
        return view('articles.create', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'designation' => 'required|string|max:255',
            'reference' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'tva' => 'required|integer|in:10,20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'quantite_stock' => 'nullable|integer|min:0',
            'seuil_minimum' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('articles.index')
            ->with('success', 'Article créé avec succès');
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $fournisseurs = Fournisseur::all();
        return view('articles.edit', compact('article','fournisseurs'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'designation' => 'required|string|max:255',
            'reference' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'tva' => 'required|integer|in:10,20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'quantite_stock' => 'nullable|integer|min:0',
            'seuil_minimum' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        // إذا ترفعات صورة جديدة نحيد القديمة
        if ($request->hasFile('image')) {

            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('articles.index')
            ->with('success', 'Article mis à jour');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article supprimé');
    }

    // Duplicate article: prefill create form
    public function duplicate(Article $article)
    {
        $duplicateData = [
            'designation' => $article->designation,
            'reference' => $article->reference,
            'prix' => $article->prix,
            'tva' => $article->tva,
            'quantite_stock' => $article->quantite_stock,
            'seuil_minimum' => $article->seuil_minimum,
            'fournisseur_id' => $article->fournisseur_id,
            'description' => $article->description,
        ];

        return redirect()->route('articles.create')
            ->with('duplicateData', $duplicateData)
            ->with('message', 'Article dupliqué — modifiez les champs puis enregistrez.');
    }
}