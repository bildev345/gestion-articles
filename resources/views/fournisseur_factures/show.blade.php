@extends('layout.app')

@section('title', 'Facture Fournisseur #' . $fournisseur_facture->numero)

@section('content')

<div class="invoice-container">
<style>
.invoice-container {
    background: white;
    padding: 15px 25px;
    max-width: 900px;
    margin: 15px auto;
    font-family: Arial, sans-serif;
    font-size: 12px;
}

.invoice-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 15px;
    border-bottom: 2px solid #333;
    padding-bottom: 10px;
}

.company-info h2 {
    color: #000;
    font-weight: bold;
    font-size: 14px;
    margin: 0 0 2px 0;
}

.company-info small {
    display: block;
    color: #333;
    font-size: 11px;
    margin: 1px 0;
}

.invoice-meta {
    text-align: right;
    font-size: 11px;
}

.invoice-meta-item {
    margin-bottom: 3px;
}

.invoice-meta-label {
    color: #333;
    font-size: 11px;
    font-weight: bold;
}

.invoice-meta-value {
    font-size: 12px;
    font-weight: bold;
    color: #000;
}

.section-title {
    font-weight: 700;
    color: #333;
    font-size: 12px;
    text-transform: uppercase;
    margin-top: 20px;
    margin-bottom: 10px;
    color: #666;
}

.client-box {
    margin: 8px 0;
    font-size: 11px;
}

.client-box .label {
    color: #999;
    font-size: 10px;
    font-weight: bold;
    text-transform: uppercase;
}

.client-box .value {
    font-weight: bold;
    font-size: 12px;
    margin: 2px 0;
}

.table-items {
    width: 100%;
    margin: 10px 0;
    border-collapse: collapse;
    font-size: 11px;
}

.table-items thead {
    background: #f0f0f0;
    border-top: 1px solid #333;
    border-bottom: 1px solid #333;
}

.table-items th,
.table-items td {
    padding: 6px 8px;
}

.table-items th:nth-child(3),
.table-items td:nth-child(3) {
    text-align: center;
    width: 12%;
}

.table-items th:nth-child(4),
.table-items td:nth-child(4) {
    text-align: right;
    width: 15%;
}

.table-items th:nth-child(5),
.table-items td:nth-child(5) {
    text-align: center;
    width: 10%;
}

.table-items th:nth-child(6),
.table-items td:nth-child(6) {
    text-align: right;
    width: 15%;
}
.table-items td {
    padding: 4px 3px;
    border-bottom: 1px solid #eee;
}

.table-items tbody tr:last-child td {
    border-bottom: 1px solid #333;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.totals-section {
    display: grid;
    grid-template-columns: 1fr 200px;
    gap: 20px;
    margin: 8px 0;
}

.totals-box {
    font-size: 11px;
}

.total-row {
    display: grid;
    grid-template-columns: 1fr 80px;
    margin-bottom: 2px;
    padding-bottom: 2px;
    border-bottom: 1px dotted #ccc;
}

.total-row.total-ht {
    font-weight: bold;
}

.total-row.total-tva {
    font-weight: bold;
}

.total-row.total-final {
    border-bottom: 1px solid #333;
    border-top: 1px solid #333;
    padding: 2px 0;
    margin-top: 2px;
    font-weight: bold;
    font-size: 12px;
}

.signature-section {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 30px;
    margin-top: 25px;
    text-align: center;
    font-size: 10px;
}

.signature-box {
    border-top: 1px solid #333;
    height: 45px;
}

.signature-label {
    font-size: 10px;
    font-weight: bold;
    color: #333;
}

@media print {
    nav, .sidebar, .no-print, .btn {
        display: none !important;
    }
    body {
        margin: 0;
        background: white;
    }
    .invoice-container {
        padding: 8px 15px;
        margin: 0;
        max-width: 100%;
    }
    @page {
        size: A4;
        margin: 5mm;
    }
}
</style>

    <!-- HEADER -->
    <div class="invoice-header">
        <div>
            <h2>Eco Ciel</h2>
            <small>Énergie solaire</small><br>
            <small>JINAN 1 RTE AIN CHKEF</small><br>
            <small>30000, Fès</small><br>
            <small>Tél: 06 61 98 93 39 - Fix: 05 32 00 28 74</small><br>
        </div>
        <div class="invoice-meta">
             <div class="meta-row" style="margin-top:5px;">
                <div class="meta-label">Achat n° : {{ $fournisseur_facture->numero }}</div>
                
            </div>
            <div class="meta-row">
                <div class="meta-label">Date : {{ \Carbon\Carbon::parse($fournisseur_facture->date_facture)->format('d/m/Y') }}</div>
                
            </div>
            <div class="client-name" style="font-size:14px; font-weight:bold; margin-bottom:3px;">
                {{ strtoupper($fournisseur_facture->fournisseur->nom) }}
            </div>
            @if($fournisseur_facture->fournisseur->adresse)
            <div style="font-size:11px; color:#333;">{{ $fournisseur_facture->fournisseur->adresse }}</div>
            @endif
            @if($fournisseur_facture->fournisseur->telephone)
            <div style="font-size:11px; color:#333;">Tel: {{ $fournisseur_facture->fournisseur->telephone }}</div>
            @endif
           
        </div>
    </div>



    <!-- ARTICLES TABLE -->
    <table class="table-items">
        <thead>
            <tr>
                <th width="5%">N°</th>
                <th width="45%">Désignation</th>
                <th width="10%" class="text-center">Qté</th>
                <th width="15%" class="text-right">Prix Unit.</th>
                <th width="10%" class="text-center">TVA</th>
                <th width="15%" class="text-right">Total HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fournisseur_facture->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->article->designation }}</td>
                <td class="text-center">{{ number_format($detail->quantite, 0) }}</td>
                <td class="text-right">{{ number_format($detail->prix_unitaire, 2, ',', ' ') }} </td>
                <td class="text-center">{{ $detail->tva }}%</td>
                <td class="text-right"><strong>{{ number_format($detail->total_ht, 2, ',', ' ') }} </strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS SECTION -->
    <div class="totals-section">
        <div></div>
        <div class="totals-box">
            <div class="total-row total-ht">
                <span>Total HT</span>
                <span>{{ number_format($fournisseur_facture->total_ht, 2, ',', ' ') }} DH</span>
            </div>
            <div class="total-row total-tva">
                <span>Total TVA</span>
                <span>{{ number_format($fournisseur_facture->total_tva, 2, ',', ' ') }} </span>
            </div>
            <div class="total-row total-final">
                <span>TOTAL TTC</span>
                <span>{{ number_format($fournisseur_facture->total_ttc, 2, ',', ' ') }} </span>
            </div>
        </div>
    </div>

    <!-- SIGNATURE SECTION -->
    <div class="signature-section">
        <div>
            <div class="signature-box"></div>
            <div class="signature-label">Signature Fournisseur</div>
        </div>
        <div>
            <div class="signature-label" style="margin-bottom: 60px;">Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
        </div>
        <div>
            <div class="signature-box"></div>
            <div class="signature-label">Tampon & Signature</div>
        </div>
    </div>

</div>

<!-- PRINT BUTTON -->
<div style="text-align: center; margin-top: 30px; gap: 10px; display: flex; justify-content: center;" class="no-print">
    <button onclick="window.print()" class="btn btn-ciel">
        <i class="bi bi-printer-fill me-1"></i> Imprimer
    </button>
    <a href="{{ route('fournisseur_factures.edit', $fournisseur_facture->id) }}" class="btn btn-warning">
        <i class="bi bi-pencil-fill me-1"></i> Modifier
    </a>
    <a href="{{ route('fournisseur_factures.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@endsection