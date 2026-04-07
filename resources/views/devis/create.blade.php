@extends('layout.app')

@section('title', 'Créer Devis')
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif



@if(session('message'))
    <div class="alert alert-info">{{ session('message') }}</div>
@endif

@section('actions')
    <a href="{{ route('devis.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
@endsection

@section('content')

    <form action="{{ route('devis.store') }}" method="POST">
        @csrf
        
        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary-ciel">
                            <i class="bi bi-receipt me-2"></i>Informations Devis
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            
                            <!-- Client -->
                            <div class="col-md-12">
                                <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                    <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner un client --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ (old('client_id') ?? session('duplicateData.client_id')) == $client->id ? 'selected' : '' }}>
                                                {{ $client->nom }} {{ $client->prenom ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date Facture -->
                            <div class="col-md-6">
                                <label for="date_facture" class="form-label">Date Facture <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                   <input type="date" 
       name="date" 
       class="form-control" 
       value="{{ old('date', date('Y-m-d')) }}" 
       required>
                                    @error('date_devis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date Échéance -->
                            <div class="col-md-6">
                                <label for="date_echeance" class="form-label">Date Échéance</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                                    <input type="date" 
                                           class="form-control @error('date_echeance') is-invalid @enderror" 
                                           id="date_echeance" 
                                           name="date_echeance" 
                                           value="{{ old('date_echeance', session('duplicateData.date_echeance')) }}">
                                    @error('date_echeance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
    <label for="mode_reglement" class="form-label">Mode de Règlement</label>
    <select name="mode_reglement" id="mode_reglement" class="form-control">
        <option value="">--- Sélectionner ---</option>
        <option value="Espèces" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
        <option value="Chèque" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
        <option value="Virement" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Virement' ? 'selected' : '' }}>Virement</option>
        <option value="Manuelle" {{ old('mode_reglement', $facture->mode_reglement ?? '') == 'Manuelle' ? 'selected' : '' }}>Manuelle</option>
    </select>
</div>

                        </div>
                    </div>
                </div>

                <!-- Articles Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary-ciel">
                            <i class="bi bi-box-seam me-2"></i>Articles
                        </h5>
                        <button type="button" id="addRow" class="btn btn-outline-ciel btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter Ligne
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="articlesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-3">Article</th>
                                        <th class="py-3" style="width: 120px;">Prix</th>
                                        <th class="py-3" style="width: 100px;">TVA</th>
                                        <th class="py-3" style="width: 120px;">Quantité</th>
                                        <th class="py-3" style="width: 140px;">Total</th>
                                        <th class="py-3" style="width: 80px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $duplicateArticles = session('duplicateData.articles', []);
                                        $rowIndex = 0;
                                    @endphp

                                    @if(count($duplicateArticles) > 0)
                                        @foreach($duplicateArticles as $article)
                                            @php
                                                $articleData = $articles->firstWhere('id', $article['id']);
                                                $rowIndex++;
                                            @endphp
                                            <tr>
    <td class="py-2">
        <select name="articles[0][id]" class="form-select articleSelect">
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
            <input type="number" step="0.01" name="articles[0][prix]" class="form-control prix" value="0">
        </div>
    </td>
    <td class="py-2">
        <div class="input-group input-group-sm">
            <input type="number" step="0.01" name="articles[0][tva]" class="form-control tva" value="0">
            <span class="input-group-text">%</span>
        </div>
    </td>
                                                <td class="py-2">
                                                    <input type="number" name="articles[{{ $rowIndex - 1 }}][quantite]" class="form-control qte" value="{{ $article['quantite'] }}" min="1">
                                                </td>
                                                <td class="py-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" name="articles[{{ $rowIndex - 1 }}][total]" class="form-control total"  value="">
                                                        <span class="input-group-text">DH</span>
                                                    </div>
                                                </td>
                                                <td class="py-2">
                                                    <button type="button" class="btn btn-outline-danger btn-sm removeRow">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="py-2">
                                                <select name="articles[0][id]" class="form-select articleSelect" required>
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
                                                    <input type="text" name="articles[0][prix]" class="form-control prix" >
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="articles[0][tva]" class="form-control tva" >
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <input type="number" name="articles[0][quantite]" class="form-control qte" value="1" min="1">
                                            </td>
                                            <td class="py-2">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="articles[0][total]" class="form-control total" >
                                                    <span class="input-group-text">DH</span>
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <button type="button" class="btn btn-outline-danger btn-sm removeRow">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Card -->
            <div class="col-lg-4">
                <!-- Actions Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary-ciel">
                            <i class="bi bi-gear me-2"></i>Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-ciel">
                                <i class="bi bi-check-lg me-1"></i> Créer Devis
                            </button>
                            <a href="{{ route('devis.index') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Totals Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary-ciel">
                            <i class="bi bi-calculator me-2"></i>Totaux
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total HT</span>
                            <span class="fw-bold" id="totalHT">0.00 DH</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total TVA</span>
                            <span class="fw-bold" id="totalTVA">0.00 DH</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total TTC</span>
                            <span class="fw-bold text-primary-ciel fs-5" id="totalTTC">0.00 DH</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

<script>
let rowIndex = 1;

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
                <input type="text" name="articles[${rowIndex}][prix]" class="form-control prix" >
            </div>
        </td>
        <td class="py-2">
            <div class="input-group input-group-sm">
                <input type="text" name="articles[${rowIndex}][tva]" class="form-control tva" >
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

// Remplir prix, TVA et calculer total
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

document.addEventListener('input', function(e){
    if(e.target.classList.contains('qte')){
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

    // TVA par taux
    let tvaGroups = {};

    document.querySelectorAll('#articlesTable tbody tr').forEach(row => {

        let prix = parseFloat(row.querySelector('.prix').value) || 0;
        let qte  = parseFloat(row.querySelector('.qte').value) || 0;
        let tva  = parseFloat(row.querySelector('.tva').value) || 0;

        let sousTotal = prix * qte;

        // Total HT global
        totalHT += sousTotal;

        // Regrouper TVA par taux
        if(!tvaGroups[tva]){
            tvaGroups[tva] = 0;
        }

        tvaGroups[tva] += sousTotal;
    });

    // Calcul TVA globale
    let totalTVA = 0;

    for (let taux in tvaGroups) {
        totalTVA += tvaGroups[taux] * (taux / 100);
    }

    let totalTTC = totalHT + totalTVA;

    document.getElementById('totalHT').textContent =
        totalHT.toFixed(2) + ' DH';

    document.getElementById('totalTVA').textContent =
        totalTVA.toFixed(2) + ' DH';

    document.getElementById('totalTTC').textContent =
        totalTTC.toFixed(2) + ' DH';
}
</script>
<script>
let rowIndex = {{ count(session('duplicateData.articles', [])) ?? 1 }};

// Ajouter ligne
document.getElementById('addRow').addEventListener('click', function() {
    let table = document.getElementById('articlesTable').getElementsByTagName('tbody')[0];
    let newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="py-2">
            <select name="articles[${rowIndex}][id]" class="form-select articleSelect">
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
                <input type="number" step="0.01" name="articles[${rowIndex}][prix]" class="form-control prix" value="0">
            </div>
        </td>
        <td class="py-2">
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" name="articles[${rowIndex}][tva]" class="form-control tva" value="0">
                <span class="input-group-text">%</span>
            </div>
        </td>
        <td class="py-2">
            <input type="number" name="articles[${rowIndex}][quantite]" class="form-control qte" value="1" min="1">
        </td>
        <td class="py-2">
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" name="articles[${rowIndex}][total]" class="form-control total" readonly>
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
    
    // Ajouter les event listeners à la nouvelle ligne
    attachEventListeners(newRow);
});

// Fonction pour attacher les event listeners
function attachEventListeners(row) {
    // Prix change
    row.querySelector('.prix').addEventListener('input', calculateRowTotal);
    // Quantité change
    row.querySelector('.qte').addEventListener('input', calculateRowTotal);
    // TVA change
    row.querySelector('.tva').addEventListener('input', calculateRowTotal);
    // Article change
    row.querySelector('.articleSelect').addEventListener('change', function(e) {
        let selected = e.target.options[e.target.selectedIndex];
        let prixInput = row.querySelector('.prix');
        let tvaInput = row.querySelector('.tva');
        
        prixInput.value = selected.dataset.prix || '0';
        tvaInput.value = selected.dataset.tva || '0';
        
        calculateRowTotal.call(prixInput);
    });
}

// Calculer total d'une ligne
function calculateRowTotal() {
    let row = this.closest('tr');
    let prix = parseFloat(row.querySelector('.prix').value) || 0;
    let qte = parseFloat(row.querySelector('.qte').value) || 0;
    let total = prix * qte;
    row.querySelector('.total').value = total.toFixed(2);
    calculateTotals();
}

// Supprimer ligne
document.addEventListener('click', function(e){
    if(e.target.classList.contains('removeRow') || e.target.closest('.removeRow')){
        let btn = e.target.classList.contains('removeRow') ? e.target : e.target.closest('.removeRow');
        let row = btn.closest('tr');
        if (document.querySelectorAll('#articlesTable tbody tr').length > 1) {
            row.remove();
            calculateTotals();
        }
    }
});

// Calculer les totaux généraux
function calculateTotals() {
    let totalHT = 0;
    let totalTVA = 0;

    document.querySelectorAll('#articlesTable tbody tr').forEach(row => {
        let prix = parseFloat(row.querySelector('.prix').value) || 0;
        let qte = parseFloat(row.querySelector('.qte').value) || 0;
        let tva = parseFloat(row.querySelector('.tva').value) || 0;
        
        let sousTotalHT = prix * qte;
        let tvaAmount = sousTotalHT * (tva / 100);
        
        totalHT += sousTotalHT;
        totalTVA += tvaAmount;
    });

    let totalTTC = totalHT + totalTVA;

    document.getElementById('totalHT').textContent = totalHT.toFixed(2) + ' DH';
    document.getElementById('totalTVA').textContent = totalTVA.toFixed(2) + ' DH';
    document.getElementById('totalTTC').textContent = totalTTC.toFixed(2) + ' DH';
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#articlesTable tbody tr').forEach(attachEventListeners);
    calculateTotals();
});
</script>
@endsection