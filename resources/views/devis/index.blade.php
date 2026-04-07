@extends('layout.app')

@section('title', 'Gestion des Devis')

@section('actions')
<a href="{{ route('devis.create') }}" class="btn btn-ciel">
  <i class="bi bi-file-earmark-text-fill me-1"></i> Nouveau Devis
</a>
@endsection

@section('content')

<form method="GET" class="mb-3">
  <div class="input-group search-ciel">
    <span class="input-group-text bg-white border-end-0">
      <i class="bi bi-search text-muted"></i>
    </span>
    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Rechercher un devis...">
    <button class="btn btn-outline-ciel" type="submit">Rechercher</button>
  </div>
</form>

<div class="table-responsive table-ciel-wrap">
  <table class="table table-custom align-middle mb-0">
    <thead>
      <tr>
        <th class="text-center">N° Devis</th>
         <th width="120" class="d-none d-md-table-cell">Date</th>
        <th>Client</th>
       
        <!-- <th width="100" class="d-none d-md-table-cell text-center">Statut</th> -->
        <th class="text-end d-none d-lg-table-cell">Total HT</th>
        <th class="text-end">Total TTC</th>
        <th class="text-end" width="170">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($devis as $d)
      <tr class="row-hover">
        <td class="text-center fw-bold text-primary-ciel">#{{ str_pad($d->id, 6, '0', STR_PAD_LEFT) }}</td>
         <td class="d-none d-md-table-cell">
          {{ $d->date ? \Carbon\Carbon::parse($d->date)->format('d/m/Y') : '-' }}
        </td>

        <td>
          <div class="fw-semibold">{{ $d->client->nom ?? 'N/A' }} {{ $d->client->prenom ?? '' }}</div>
          <div class="small text-muted">
            {{ $d->date ? \Carbon\Carbon::parse($d->date)->format('d/m/Y') : '' }}
          </div>
        </td>

       
<!-- 
        <td class="d-none d-md-table-cell text-center">
          @switch($d->statut)
            @case('brouillon')
              <span class="badge bg-warning text-dark">Brouillon</span>
              @break
            @case('validé')
              <span class="badge bg-success">Validé</span>
              @break
            @case('refusé')
              <span class="badge bg-danger">Refusé</span>
              @break
            @case('converti')
              <span class="badge bg-info text-dark">Converti</span>
              @break
            @default
              <span class="badge bg-secondary">N/A</span>
          @endswitch
        </td> -->

        <td class="text-end d-none d-lg-table-cell">
          {{ number_format($d->total_ht, 2, ',', ' ') }} DH
        </td>

        <td class="text-end fw-bold">
          {{ number_format($d->total_ttc, 2, ',', ' ') }} DH
        </td>

        <td class="text-end">
          <div class="d-inline-flex gap-1">
            <a href="{{ route('devis.show', $d->id) }}" class="btn btn-action action-view" data-bs-toggle="tooltip" title="Voir">
              <i class="bi bi-eye-fill"></i>
            </a>
             <a href="{{ route('devis.duplicate', $d->id) }}" class="btn btn-action action-dup" data-bs-toggle="tooltip" title="Dupliquer">
              <i class="bi bi-files"></i>
            </a>
            <a href="{{ route('devis.edit', $d->id) }}" class="btn btn-action action-edit" data-bs-toggle="tooltip" title="Modifier">
              <i class="bi bi-pencil-fill"></i>
            </a>
            <form action="{{ route('devis.destroy', $d->id) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="button" class="btn btn-action action-del btn-delete-confirm" data-message="Êtes-vous sûr de vouloir supprimer ce devis ?" data-bs-toggle="tooltip" title="Supprimer">
                <i class="bi bi-trash-fill"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-5 text-muted">
          <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
          Aucun devis trouvé
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection