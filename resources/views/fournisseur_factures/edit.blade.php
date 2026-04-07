@extends('layout.app')

@section('title', 'Modifier Facture Fournisseur')

@section('actions')
    <a href="{{ route('fournisseur_factures.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
@endsection

@section('content')

<form action="{{ route('fournisseur_factures.update', $fournisseur_facture->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-ciel">
                        <i class="bi bi-receipt me-2"></i>Informations Facture Fournisseur
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="fournisseur_id" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                <select name="fournisseur_id" id="fournisseur_id" class="form-select @error('fournisseur_id') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner un fournisseur --</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $fournisseur_facture->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                            {{ $fournisseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fournisseur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="date_facture" class="form-label">Date Facture <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" 
                                       class="form-control @error('date_facture') is-invalid @enderror" 
                                       id="date_facture" 
                                       name="date_facture" 
                                       value="{{ old('date_facture', \Carbon\Carbon::parse($fournisseur_facture->date_facture)->format('Y-m-d')) }}" 
                                       required>
                                @error('date_facture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

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
                                @foreach($fournisseur_facture->details as $index => $detail)
                                    <tr>
                                        <td class="py-2">
                                            <select name="articles[{{ $index }}][id]" class="form-select articleSelect" required>
                                                <option value="">-- Sélectionner --</option>
                                                @foreach($articles as $article)
                                                    <option value="{{ $article->id }}" data-prix="{{ $article->prix }}" data-tva="{{ $article->tva }}"
                                                        {{ old('articles.'.$index.'.id', $detail->article_id) == $article->id ? 'selected' : '' }}>
                                                        {{ $article->designation }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-2">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">DH</span>
                                                <input type="text" name="articles[{{ $index }}][prix]" class="form-control prix" value="{{ old('articles.'.$index.'.prix', $detail->prix_unitaire) }}">
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="articles[{{ $index }}][tva]" class="form-control tva" value="{{ old('articles.'.$index.'.tva', $detail->tva) }}" readonly>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <input type="number" name="articles[{{ $index }}][quantite]" class="form-control qte" value="{{ old('articles.'.$index.'.quantite', $detail->quantite) }}" min="1">
                                        </td>
                                        <td class="py-2">
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="articles[{{ $index }}][total]" class="form-control total" value="{{ old('articles.'.$index.'.total', $detail->total_ht) }}" readonly>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-ciel">
                        <i class="bi bi-gear me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-ciel">
                            <i class="bi bi-check-lg me-1"></i> Modifier Achat
                        </button>
                        <a href="{{ route('fournisseur_factures.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>

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
let rowIndex = {{ $fournisseur_facture->details->count() }};

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
    if(e.target.classList.contains('qte') || e.target.classList.contains('prix')){
        let row = e.target.closest('tr');
        let qte = parseFloat(row.querySelector('.qte').value) || 0;
        let prix = parseFloat(row.querySelector('.prix').value) || 0;
        row.querySelector('.total').value = (qte * prix).toFixed(2);
        calculateTotals();
    }
});

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

calculateTotals();
</script>

@endsection