@extends('layout.app')

@section('title', 'Gestion des Fournisseurs')

@section('actions')
<a href="{{ route('fournisseurs.create') }}" class="btn btn-ciel">
  <i class="bi bi-plus-circle me-1"></i> Nouveau Fournisseur
</a>
@endsection

@section('content')

<form method="GET" class="mb-3">
  <div class="input-group search-ciel">
    <span class="input-group-text bg-white border-end-0">
      <i class="bi bi-search text-muted"></i>
    </span>
    <input type="text" name="search" class="form-control border-start-0" value="{{ request('search') }}" placeholder="Rechercher un fournisseur...">
    <button class="btn btn-outline-ciel" type="submit">Rechercher</button>
  </div>
</form>

<div class="table-responsive table-ciel-wrap">
  <table class="table table-custom align-middle mb-0">
    <thead>
      <tr>
        <th class="text-center" width="90">N°</th>
        <th width="130" class="d-none d-md-table-cell">Date</th>
        <th>Nom</th>
        <th>Téléphone</th>
        <th class="d-none d-lg-table-cell">Adresse</th>
        <th class="d-none d-lg-table-cell">Ville</th>
        <th class="text-end" width="140">Actions</th>
      </tr>
    </thead>

    <tbody>
      @forelse($fournisseurs as $f)
      <tr class="row-hover">
        <td class="text-center fw-bold text-primary-ciel">N°{{ str_pad($f->id, 3, '0', STR_PAD_LEFT) }}</td>
        <td class="d-none d-md-table-cell">{{ $f->date ?? $f->created_at->format('Y-m-d') }}</td>

        <td class="fw-semibold">{{ $f->nom }}</td>

        <td>
          @if($f->telephone)
            <a class="link-ciel text-decoration-none" href="tel:{{ $f->telephone }}">
              <i class="bi bi-telephone me-1"></i>{{ $f->telephone }}
            </a>
          @else
            <span class="text-muted">-</span>
          @endif
        </td>

        <td class="d-none d-lg-table-cell">{{ $f->adresse }}</td>
        <td class="d-none d-lg-table-cell">{{ $f->ville }}</td>

        <td class="text-end">
          <div class="d-inline-flex gap-1">
          
            <a href="{{ route('fournisseurs.edit', $f) }}" class="btn btn-action action-edit" data-bs-toggle="tooltip" title="Modifier">
              <i class="bi bi-pencil-fill"></i>
            </a>
           
              
            <form action="{{ route('fournisseurs.destroy', $f) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button class="btn btn-action action-del btn-delete-confirm" type="button" data-message="Êtes-vous sûr de vouloir supprimer ce fournisseur ?" data-bs-toggle="tooltip" title="Supprimer">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-5 text-muted">
          <i class="bi bi-truck fs-1 d-block mb-2"></i>
          Aucun fournisseur
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection