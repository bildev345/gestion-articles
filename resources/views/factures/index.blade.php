@extends('layout.app')

@section('title', 'Gestion des Ventes')

@section('actions')
<a href="{{ route('factures.create') }}" class="btn btn-ciel">
  <i class="bi bi-box-seam-fill me-1"></i> Nouvelle Vente
</a>
@endsection

@section('content')

<form method="GET" class="mb-3">
  <div class="input-group search-ciel">
    <span class="input-group-text bg-white border-end-0">
      <i class="bi bi-search text-muted"></i>
    </span>
    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Rechercher une facture...">
    <button class="btn btn-outline-ciel" type="submit">Rechercher</button>
  </div>
</form>

<div class="table-responsive table-ciel-wrap">
  <table class="table table-custom align-middle mb-0">
    <thead>
      <tr>
        <th class="text-center">N° Facture</th>
        <th>Client</th>
        <th width="120" class="d-none d-md-table-cell">Date</th>
        <th class="text-end d-none d-lg-table-cell">Total HT</th>
        <th class="text-end">Total TTC</th>
        <th class="text-end" width="170">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($factures as $facture)
      <tr class="row-hover">
        <td class="text-center fw-bold text-primary-ciel">{{ $facture->numero }}</td>

        <td>
          <div class="fw-semibold">{{ $facture->client->nom ?? 'N/A' }}</div>
          <div class="small text-muted d-md-none">{{ $facture->date ? \Carbon\Carbon::parse($facture->date)->format('d/m/Y') : '' }}</div>
        </td>

        <td class="d-none d-md-table-cell">
          {{ \Carbon\Carbon::parse($facture->date)->format('d/m/Y') }}
        </td>

        <td class="text-end d-none d-lg-table-cell">
          {{ number_format($facture->total_ht, 2, ',', ' ') }} DH
        </td>

        <td class="text-end fw-bold">
          {{ number_format($facture->total_ttc, 2, ',', ' ') }} DH
        </td>

        <td class="text-end">
          <div class="d-inline-flex gap-1">
            <a href="{{ route('factures.show', $facture->id) }}" class="btn btn-action action-view" data-bs-toggle="tooltip" title="Voir">
              <i class="bi bi-eye-fill"></i>
            </a>
            <a href="{{ route('factures.duplicate', $facture->id) }}" class="btn btn-action action-dup" data-bs-toggle="tooltip" title="Dupliquer">
              <i class="bi bi-files"></i>
            </a>
            <a href="{{ route('factures.edit', $facture->id) }}" class="btn btn-action action-edit" data-bs-toggle="tooltip" title="Modifier">
              <i class="bi bi-pencil-fill"></i>
            </a>
            <form action="{{ route('factures.destroy', $facture->id) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="button" class="btn btn-action action-del btn-delete-confirm" data-message="Êtes-vous sûr de vouloir supprimer cette facture ?" data-bs-toggle="tooltip" title="Supprimer">
                <i class="bi bi-trash-fill"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center py-5 text-muted">
          <i class="bi bi-receipt fs-1 d-block mb-2"></i>
          Aucune facture trouvée
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection