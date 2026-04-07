@extends('layout.app')

@section('title', 'Gestion des Achats')

@section('actions')
<a href="{{ route('fournisseur_factures.create') }}" class="btn btn-ciel">
  <i class="bi bi-plus-lg me-1"></i> Nouvelle Achat
</a>
@endsection

@section('content')

<form method="GET" class="mb-3">
  <div class="input-group search-ciel">
    <span class="input-group-text bg-white border-end-0">
      <i class="bi bi-search text-muted"></i>
    </span>
    <input type="text" name="search" class="form-control border-start-0" value="{{ request('search') }}" placeholder="Rechercher une facture fournisseur...">
    <button class="btn btn-outline-ciel" type="submit">Rechercher</button>
  </div>
</form>

<div class="table-responsive table-ciel-wrap">
  <table class="table table-custom align-middle mb-0">
    <thead>
      <tr>
        <th class="text-center" width="90">N°</th>
        <th>Fournisseur</th>
        <th class="text-center">Numéro</th>
        <th width="130" class="d-none d-md-table-cell text-center">Date</th>
        <th class="text-end d-none d-lg-table-cell">Total HT</th>
        <th class="text-end d-none d-lg-table-cell">Total TVA</th>
        <th class="text-end">Total TTC</th>
        <th class="text-end" width="170">Actions</th>
      </tr>
    </thead>

    <tbody>
      @forelse($factures as $facture)
      <tr class="row-hover">
        <td class="text-center fw-bold text-primary-ciel">N°{{ str_pad($facture->id, 3, '0', STR_PAD_LEFT) }}</td>
        <td class="fw-semibold">{{ $facture->fournisseur->nom ?? '-' }}</td>
        <td class="text-center">{{ $facture->numero }}</td>

        <td class="text-center d-none d-md-table-cell">
          {{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}
        </td>

        <td class="text-end d-none d-lg-table-cell">{{ number_format($facture->total_ht, 2, ',', ' ') }} DH</td>
        <td class="text-end d-none d-lg-table-cell">{{ number_format($facture->total_tva, 2, ',', ' ') }} DH</td>
        <td class="text-end fw-bold">{{ number_format($facture->total_ttc, 2, ',', ' ') }} DH</td>

        <td class="text-end">
          <div class="d-inline-flex gap-1">
            <a href="{{ route('fournisseur_factures.show', $facture->id) }}" class="btn btn-action action-view" data-bs-toggle="tooltip" title="Voir">
              <i class="bi bi-eye-fill"></i>
            </a>
            <a href="{{ route('fournisseur_factures.duplicate', $facture->id) }}" class="btn btn-action action-dup" data-bs-toggle="tooltip" title="Dupliquer">
              <i class="bi bi-files"></i>
            </a>
            <a href="{{ route('fournisseur_factures.edit', $facture->id) }}" class="btn btn-action action-edit" data-bs-toggle="tooltip" title="Modifier">
              <i class="bi bi-pencil-fill"></i>
            </a>
            <form action="{{ route('fournisseur_factures.destroy', $facture->id) }}" method="POST" class="d-inline">
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
        <td colspan="8" class="text-center py-5 text-muted">
          <i class="bi bi-cart-check fs-1 d-block mb-2"></i>
          Aucune facture fournisseur pour le moment.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection