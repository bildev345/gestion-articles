@extends('layout.app')


@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@section('content')
<form action="{{ route('devis.update', $devis) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Infos -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>
                        Modifier Devis {{ $devis->numero ?? '#' . $devis->id }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Numéro -->
                        <div class="col-md-6">
                            <label class="form-label">Numéro <span class="text-danger">*</span></label>
                            <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" 
                                   value="{{ old('numero', $devis->numero) }}" required>
                            @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Client -->
                        <div class="col-md-6">
                            <label class="form-label">Client <span class="text-danger">*</span></label>
                            <select name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">Sélectionner</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $devis->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->nom }} {{ $client->prenom ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- ✅ DATE CORRIGÉE -->
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $facture->date ?? date('Y-m-d')) }}" required>
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- ✅ DATE ÉCHÉANCE CORRIGÉE -->
                        <div class="col-md-6">
                            <label class="form-label">Échéance</label>
                            <input type="date" name="date_echeance" class="form-control" 
                                   value="{{ old('date_echeance', $facture->date_echeance ?? '') }}">
                        </div>

                        <!-- Mode -->
                        <div class="col-md-6">
                            <label class="form-label">Paiement</label>
                            <select name="mode_reglement" class="form-select">
                                <option value="">Sélectionner</option>
                                <option value="Espèces" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                <option value="Chèque" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                <option value="Virement" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Virement' ? 'selected' : '' }}>Virement</option>
                                <option value="Manuelle" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Manuelle' ? 'selected' : '' }}>Manuelle</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles ✅ PRIX MODIFIABLE -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between">
                    <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Articles</h6>
                    <button type="button" id="addRow" class="btn btn-sm btn-outline-primary">Ajouter</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="articlesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Article</th>
                                <th style="width: 130px;">Prix</th>
                                <th style="width: 100px;">TVA %</th>
                                <th style="width: 100px;">Qté</th>
                                <th style="width: 130px;">Total</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($devis->details as $index => $detail)
                            <tr>
                                <td>
                                    <select name="articles[{{ $index }}][id]" class="form-select articleSelect">
                                        <option value="">Libre</option>
                                        @foreach($articles as $art)
                                            <option value="{{ $art->id }}" data-prix="{{ $art->prix }}" data-tva="{{ $art->tva }}"
                                                {{ $detail->article_id == $art->id ? 'selected' : '' }}>
                                                {{ $art->designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">DH</span>
                                        <input type="number" step="0.01" name="articles[{{ $index }}][prix]" 
                                               class="form-control prix" value="{{ $detail->prix_unitaire }}" min="0">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" name="articles[{{ $index }}][tva]" 
                                               class="form-control tva" value="{{ $detail->tva }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </td>
                                <td><input type="number" name="articles[{{ $index }}][quantite]" class="form-control qte" value="{{ $detail->quantite }}" min="1"></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" name="articles[{{ $index }}][total]" class="form-control total" readonly>
                                        <span class="input-group-text">DH</span>
                                    </div>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger removeRow"><i class="bi bi-trash"></i></button></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Aucun article - Ajoutez-en !</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-gear"></i> Actions</h6></div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-lg"></i> Modifier</button>
                        <a href="{{ route('devis.show', $devis) }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-calculator"></i> Totaux</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Total HT</span><span id="totalHT">{{ number_format($facture->total_ht ?? 0, 2) }} DH</span></div>
                    <div class="d-flex justify-content-between mb-3"><span>Total TVA</span><span id="totalTVA">{{ number_format($facture->total_tva ?? 0, 2) }} DH</span></div>
                    <hr>
                    <div class="d-flex justify-content-between"><span class="h6">Total TTC</span><span class="h5 text-primary fw-bold" id="totalTTC">{{ number_format($facture->total_ttc ?? 0, 2) }} DH</span></div>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    
    let rowIndex = {{ count($devis->details ?? []) }};


// Ajouter ligne
document.getElementById('addRow').addEventListener('click', function() {
    let table = document.getElementById('articlesTable').getElementsByTagName('tbody')[0];
    let newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="py-2">
            <select name="articles[${rowIndex}][id]" class="form-select articleSelect" required>
                <option value="">-- Sélectionner --</option>
                @foreach($articles as $article)
                    <option value="{{ $article->id }}" 
                            data-prix="{{ $article->prix }}" 
                            data-tva="{{ $article->tva }}">
                        {{ $article->designation }}
                    </option>
                @endforeach
            </select>
        </td>
        <td class="py-2">
            <div class="input-group input-group-sm">
                <span class="input-group-text">DH</span>
                <input type="text" name="articles[${rowIndex}][prix]" class="form-control prix">
            </div>
        </td>
        <td class="py-2">
            <div class="input-group input-group-sm">
                <input type="text" name="articles[${rowIndex}][tva]" class="form-control tva" readonly>
                <span class="input-group-text">%</span>
            </div>
        </td>
        <td class="py-2">
            <input type="number" name="articles[${rowIndex}][quantite]" class="form-control qte" value="1" min="1">
        </td>
        <td class="py-2">
            <div class="input-group input-group-sm">
                <input type="text" name="articles[${rowIndex}][total]" class="form-control total" readonly>
                <span class="input-group-text">DH</span>
            </div>
        </td>
        <td class="py-2">
            <button type="button" class="btn btn-outline-danger btn-sm removeRow">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    table.appendChild(newRow);
    rowIndex++;
    calculateTotals();
});

// Supprimer ligne
document.addEventListener('click', function(e){
    if(e.target.classList.contains('removeRow') || e.target.closest('.removeRow')){
        let btn = e.target.classList.contains('removeRow') ? e.target : e.target.closest('.removeRow');
        let row = btn.closest('tr');
        if (document.querySelectorAll('#articlesTable tbody tr').length > 1) {
            row.remove();
            calculateTotals();
        } else {
            alert('Vous devez avoir au moins un article');
        }
    }
});

// Remplir prix, TVA et calculer total quand on change l'article
document.addEventListener('change', function(e){
    if(e.target.classList.contains('articleSelect')){
        let selected = e.target.options[e.target.selectedIndex];
        let row = e.target.closest('tr');

        row.querySelector('.prix').value = selected.dataset.prix || '0';
        row.querySelector('.tva').value = selected.dataset.tva || '0';

        let qte = parseFloat(row.querySelector('.qte').value) || 0;
        let prix = parseFloat(row.querySelector('.prix').value) || 0;

        row.querySelector('.total').value = (qte * prix).toFixed(2);
        calculateTotals();
    }
});

// Recalcul quand quantité ou prix changent
document.addEventListener('input', function(e){
    if(e.target.classList.contains('qte') || e.target.classList.contains('prix')){
        let row = e.target.closest('tr');
        let qte = parseFloat(row.querySelector('.qte').value) || 0;
        let prix = parseFloat(row.querySelector('.prix').value) || 0;

        row.querySelector('.total').value = (qte * prix).toFixed(2);
        calculateTotals();
    }
});

// Calculer les totaux généraux
function calculateTotals() {
    let totalHT = 0;
    let tvaGroups = {};

    document.querySelectorAll('#articlesTable tbody tr').forEach(row => {
        let prix = parseFloat(row.querySelector('.prix').value) || 0;
        let qte  = parseFloat(row.querySelector('.qte').value) || 0;
        let tva  = parseFloat(row.querySelector('.tva').value) || 0;

        let sousTotal = prix * qte;

        row.querySelector('.total').value = sousTotal.toFixed(2);

        totalHT += sousTotal;

        if(!tvaGroups[tva]){
            tvaGroups[tva] = 0;
        }

        tvaGroups[tva] += sousTotal;
    });

    let totalTVA = 0;

    for (let taux in tvaGroups) {
        totalTVA += tvaGroups[taux] * (taux / 100);
    }

    let totalTTC = totalHT + totalTVA;

    document.getElementById('totalHT').textContent = totalHT.toFixed(2) + ' DH';
    document.getElementById('totalTVA').textContent = totalTVA.toFixed(2) + ' DH';
    document.getElementById('totalTTC').textContent = totalTTC.toFixed(2) + ' DH';
}

// Exécuter au chargement
calculateTotals();
</script>

@endsection