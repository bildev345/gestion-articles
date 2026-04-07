<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class StockController extends Controller
{
    public function index(Request $request)
{
    $articles = Article::query()
   ->when($request->filled('search'), function ($q) use ($request) {
    $q->where('designation', 'like', '%' . $request->search . '%');
})
    ->get();

    foreach ($articles as $article) {
        $article->alert = $article->quantite_stock <= $article->seuil_minimum;
    }

    return view('stock.index', compact('articles'));
}
}