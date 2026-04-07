<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\FournisseurFactureController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevisController;  // ✅ Import du DevisController


Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
Route::middleware('auth')->group(function () {
    
// create خاصها تجي قبل

Route::get('/clients/situation', [ClientController::class, 'situation'])
    ->name('clients.situation');
Route::get('/clients/restes', [ClientController::class, 'reste'])
    ->name('clients.reste');

/* ================== DUPLICATES (BEFORE RESOURCES) ================== */

// Fournisseurs
Route::get('fournisseurs/{fournisseur}/duplicate', [FournisseurController::class, 'duplicate'])
    ->name('fournisseurs.duplicate');

// Clients
Route::get('clients/{client}/duplicate', [ClientController::class, 'duplicate'])
    ->name('clients.duplicate');

// Articles
Route::get('articles/{article}/duplicate', [ArticleController::class, 'duplicate'])
    ->name('articles.duplicate');

// Factures (ventes)
Route::get('factures/{facture}/duplicate', [FactureController::class, 'duplicate'])
    ->name('factures.duplicate');

// Fournisseur factures (achats)
Route::get('fournisseur_factures/{fournisseur_facture}/duplicate', [FournisseurFactureController::class, 'duplicate'])
    ->name('fournisseur_factures.duplicate');


/* ================== RESOURCES ================== */

Route::resource('fournisseurs', FournisseurController::class);
Route::resource('clients', ClientController::class);
Route::resource('articles', ArticleController::class);
Route::resource('factures', FactureController::class);
Route::get('/factures/create', [FactureController::class, 'create'])
    ->name('factures.create');

// من بعد show
Route::get('/factures/{facture}', [FactureController::class, 'show'])
    ->name('factures.show');
Route::resource('fournisseur_factures', FournisseurFactureController::class);


/* ================== DOWNLOADS ================== */

Route::get('factures/{facture}/download', [FactureController::class, 'download'])
    ->name('factures.download');

Route::get('fournisseur_factures/{fournisseur_facture}/download', [FournisseurFactureController::class, 'download'])
    ->name('fournisseur_factures.download');


// ===== LISTE =====
Route::get('/devis', [DevisController::class, 'index'])->name('devis.index');

// ===== CRÉER =====
Route::get('/devis/create', [DevisController::class, 'create'])->name('devis.create');
Route::post('/devis', [DevisController::class, 'store'])->name('devis.store');

// ===== VOIR / MODIFIER / SUPPRIMER =====
Route::get('devis/{devis}', [DevisController::class, 'show'])->name('devis.show');
Route::get('devis/{devis}/edit', [DevisController::class, 'edit'])->name('devis.edit');
Route::put('devis/{devis}', [DevisController::class, 'update'])->name('devis.update');
Route::delete('devis/{devis}', [DevisController::class, 'destroy'])->name('devis.destroy');
Route::get('devis/{devis}/download', [DevisController::class, 'download'])->name('devis.download');
Route::get('devis/{devis}/duplicate', [DevisController::class, 'duplicate'])->name('devis.duplicate');
Route::get('/devis/restes', [DevisController::class, 'situation'])->name('devis.situation');
/* ================== PAGES ================== */
Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
