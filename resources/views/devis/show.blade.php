@extends('layout.app')

@section('title', 'DEVIS  ')

@section('content')

<div class="invoice-container" id="printArea">
<style>
/* ===== RESET & BASE ===== */
* { box-sizing: border-box; }

.invoice-container {
    background: white;
    padding: 25px 35px;
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
    font-size: 12px;
    color: #000;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    outline: none !important;
}

/* ===== SUPPRIME BORDURES BOOTSTRAP ===== */
.card,
.card-body,
.content-wrapper,
.main-content {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
}

/* ===== HEADER ===== */
.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 12px;
}

.company-info h2 {
    font-size: 18px;
    font-weight: bold;
    color: #000;
    margin: 0 0 3px 0;
}

.company-info .subtitle {
    font-size: 11px;
    color: #444;
    font-style: italic;
    display: block;
    margin-bottom: 8px;
}

.company-info .addr-line {
    font-size: 11px;
    color: #333;
    display: block;
    line-height: 1.5;
}

/* ===== META FACTURE (droite) ===== */
.invoice-meta {
    text-align: right;
    font-size: 11px;
}

.meta-grid {
    display: grid;
    grid-template-columns: auto auto;
    gap: 2px 12px;
    text-align: left;
    margin-bottom: 15px;
}

.meta-label {
    color: #000;
    font-weight: normal;
    font-size: 11px;
}

.meta-value {
    font-weight: bold;
    font-size: 11px;
    color: #000;
}

.client-block {
    text-align: right;
    margin-top: 10px;
}

.client-block .client-title {
    font-size: 13px;
    font-weight: bold;
    letter-spacing: 0.5px;
    color: #000;
}

.client-block .client-sub {
    font-size: 11px;
    color: #444;
    margin-top: 2px;
    display: block;
}

/* ===== SEPARATEUR ===== */
.header-separator {
    border: none;
    border-top: 1.5px solid #333;
    margin: 0 0 20px 0;
}

/* ===== "FACTURE en MAD" label ===== */
.facture-currency {
    text-align: center;
    font-weight: bold;
    font-size: 13px;
    margin: 15px 0 8px 0;
    color: #000;
    letter-spacing: 0.5px;
}

/* ===== TABLE SANS LIGNES ===== */
.table-items {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    margin-bottom: 5px;
    border: none !important;
}

.table-items thead tr {
    border-top: 1.5px solid #333;
    border-bottom: 1.5px solid #333;
}

.table-items th {
    padding: 7px 8px;
    font-weight: bold;
    font-size: 11px;
    color: #000;
    background: #fff;
    border: none !important;
}

.table-items th:first-child   { text-align: center; }
.table-items th.col-designation { text-align: left; }
.table-items th.col-tva        { text-align: center; }
.table-items th.col-prix       { text-align: right; }
.table-items th.col-total      { text-align: right; }

/* ===== CELLULES SANS BORDURE ===== */
.table-items td {
    padding: 7px 8px;
    border: none !important;
    vertical-align: middle;
    font-size: 11px;
}

.table-items td:first-child     { text-align: center; }
.table-items td.col-designation { text-align: left; }
.table-items td.col-tva         { text-align: center; }
.table-items td.col-prix        { text-align: right; }
.table-items td.col-total       { text-align: right; font-weight: bold; }

/* Ligne de fermeture après le dernier article */
.table-items tbody tr.last-data-row td {
    border-bottom: 1.5px solid #333 !important;
}

/* Lignes vides */
.empty-rows td {
    height: 22px;
    border: none !important;
}

/* Dernière ligne vide = fermeture du tableau */
.empty-rows.last-empty td {
    border-bottom: 1.5px solid #333 !important;
}

/* ===== TOTAUX ===== */
.totals-wrapper {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
    margin-bottom: 15px;
}

.totals-box {
    min-width: 260px;
    font-size: 11px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
}

.total-row .t-label { color: #333; }

.total-row .t-value {
    font-weight: bold;
    color: #000;
    min-width: 100px;
    text-align: right;
}

.total-row.total-final {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
    padding: 5px 0;
    margin-top: 5px;
}

.total-row.total-final .t-label,
.total-row.total-final .t-value {
    font-size: 13px;
    font-weight: bold;
}

/* ===== SIGNATURE ===== */
.signature-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 40px;
    margin-bottom: 20px;
    font-size: 10px;
}

.sig-block {
    text-align: center;
    min-width: 150px;
}

.sig-line {
    border-top: 1px solid #333;
    margin-bottom: 5px;
    height: 50px;
}

.sig-label {
    font-size: 10px;
    font-weight: bold;
    color: #333;
}

.date-block {
    text-align: center;
    font-size: 10px;
    font-weight: bold;
}

/* ===== FOOTER ===== */
.footer-info {
    border-top: 1px solid #ccc;
    margin-top: 30px;
    padding-top: 8px;
    font-size: 9px;
    color: #666;
    text-align: center;
    line-height: 1.8;
}

/* ===== PRINT ===== */
@media print {
    nav, .sidebar, .no-print, .btn,
    header, footer, aside,
    .facture-paiement-manager {
        display: none !important;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-container {
        padding: 10px 15px !important;
        margin: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        box-shadow: none !important;
        border: none !important;
    }

    .card,
    .card-body,
    .content-wrapper,
    .main-content {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    @page {
        size: A4 portrait;
        margin: 8mm 10mm;
    }
}
</style>

<!-- ======== HEADER ======== -->
<div class="invoice-header">

    <!-- GAUCHE : Infos société -->
    <div class="company-info">
        <h2>EcoCiel</h2>
        <span class="subtitle">Énergie solaire</span>
        <span class="addr-line">JINAN 1 RTE AIN CHKEF</span>
        <span class="addr-line">30000, Fès</span>
        <span class="addr-line" style="margin-top:5px;">
            Tél : 06 61 98 93 39 &nbsp;-&nbsp; Fix : 05 32 00 28 74
        </span>
        <span class="addr-line">ecociel1@gmail.com</span>
    </div>

    <!-- DROITE : Numéro facture + Client -->
    <div class="invoice-meta">
        <div class="meta-grid">
            <span class="meta-label">Devis n°</span>
            <span class="meta-value">{{ $devis->numero }}</span>

            <span class="meta-label">Date</span>
            <span class="meta-value">
                {{ \Carbon\Carbon::parse($devis->date)->format('d/m/Y') }}
            </span>

            <span class="meta-label">N° client</span>
            <span class="meta-value">{{ $devis->client->id ?? '' }}</span>
        </div>

        <div class="client-block">
            <div class="client-title">
                {{ strtoupper($devis->client->nom) }}
            </div>
            @if($devis->client->adresse)
                <span class="client-sub">{{ $devis->client->adresse }}</span>
            @endif
            @if($devis->client->telephone)
                <span class="client-sub">
                    Tél : {{ $devis->client->telephone }}
                </span>
            @endif
        </div>
    </div>
</div>

<hr class="header-separator">

<!-- ======== LABEL DEVISE ======== -->
<div class="facture-currency">Devis en MAD</div>

<!-- ======== TABLEAU ======== -->
<table class="table-items">
    <thead>
        <tr>
            <th width="7%">Qté</th>
            <th class="col-designation" width="48%">Désignation</th>
            <th class="col-tva"   width="10%">Tva</th>
            <th class="col-prix"  width="18%">Prix Unit.</th>
            <th class="col-total" width="17%">Total HT</th>
        </tr>
    </thead>
    <tbody>
        @php $totalDetails = count($devis->details); $emptyRows = max(0, 5 - $totalDetails); @endphp

        @foreach($devis->details as $index => $detail)
        <tr class="{{ ($index == $totalDetails - 1 && $emptyRows == 0) ? 'last-data-row' : '' }}">
            <td>{{ number_format($detail->quantite, 0) }}</td>
            <td class="col-designation">{{ $detail->article->designation }}</td>
            <td class="col-tva">
                {{ $detail->tva > 0 ? $detail->tva . '%' : '' }}
            </td>
            <td class="col-prix">
                {{ number_format($detail->prix_unitaire, 2, ',', ' ') }}
            </td>
            <td class="col-total">
                {{ number_format($detail->total_ht, 2, ',', ' ') }}
            </td>
        </tr>
        @endforeach

        {{-- Lignes vides --}}
        @for($i = 0; $i < $emptyRows; $i++)
        <tr class="empty-rows {{ $i == $emptyRows - 1 ? 'last-empty' : '' }}">
            <td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
    </tbody>
</table>

<!-- ======== TOTAUX + PAIEMENT (LEFT / RIGHT) ======== -->
<div style="display: flex; justify-content: space-between; margin-top:15px;">

    <!-- ===== LEFT : Paiement ===== -->
    <div style="width: 45%;">
        <div class="totals-box">

            <div class="total-row">
                <span class="t-label">Mode de Règlement</span>
                <span class="t-value">
                    {{ $devis->mode_reglement ?? 'Manuelle' }}
                </span>
            </div>

           <div class="total-row">
                <span class="t-label">Reste à payer</span>
                <span class="t-value">
                    {{ number_format($devis->reste, 2, ',', ' ') }}
                </span>
            </div>

            <div class="total-row">
                <span class="t-label">Montant en lettres</span>
                <span class="t-value">
                    {{ \App\Helpers\NumberToWords::convert($devis->total_ttc) }} Dirhams
                </span>
            </div>

        </div>
    </div>

    <!-- ===== RIGHT : Totaux ===== -->
    <div style="width: 45%; display:flex; justify-content:flex-end;">
        <div class="totals-box">

            <div class="total-row">
                <span class="t-label">Total HT</span>
                <span class="t-value">
                    {{ number_format($devis->total_ht, 2, ',', ' ') }}
                </span>
            </div>

            @if($devis->total_tva > 0)
            <div class="total-row">
                <span class="t-label">Total TVA</span>
                <span class="t-value">
                    {{ number_format($devis->total_tva, 2, ',', ' ') }}
                </span>
            </div>
            @endif

            <div class="total-row total-final">
                <span class="t-label">Total TTC</span>
                <span class="t-value">
                    {{ number_format($devis->total_ttc, 2, ',', ' ') }}
                </span>
            </div>

        </div>
    </div>

</div>
<!-- ======== SIGNATURES ======== -->
<div class="signature-section">
    <div class="sig-block">
        <div class="sig-line"></div>
        <div class="sig-label">Signature Client</div><br><br><br><br><br><br>
    </div>
    <div class="sig-block">
        <div class="sig-line"></div>
        <div class="sig-label">Signature</div><br><br><br><br><br><br>
    </div>
</div>

<!-- ======== FOOTER ======== -->
<div class="footer-info">
    N° d'identifiant fiscal : 45866738 &nbsp;|&nbsp;
    N° de taxe professionnelle : 14550637 &nbsp;|&nbsp;
    N° du registre du tribunal de commerce de Fès : 63097<br>
    N° d'affiliation à la CNSS : 2010671 &nbsp;|&nbsp;
    I.C.E : 002553200000079
</div>

</div><!-- fin .invoice-container -->

<!-- ======== BOUTONS (cachés à l'impression) ======== -->
<div style="text-align:center; margin-top:25px; gap:10px;
            display:flex; justify-content:center;"
     class="no-print">

    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer-fill me-1"></i> Imprimer Facture
    </button>

    <a href="{{ route('devis.edit', $devis->id) }}"
       class="btn btn-warning">
        <i class="bi bi-pencil-fill me-1"></i> Modifier
    </a>

    <a href="{{ route('devis.index') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<!-- ======== PAIEMENT MANAGER (MASQUÉ À L'IMPRESSION) ======== -->
<div class="no-print mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Gestion des Paiements</h5>
        </div>
        <div class="card-body">
            <livewire:devis-paiement-manager :devis="$devis" />
        </div>
    </div>
</div>

@endsection