@extends('layout.app')

@section('title','Gestion des Articles')

@section('actions')
<a href="{{ route('articles.create') }}" class="btn btn-ciel">
  <i class="bi bi-plus-circle me-1"></i> Nouveau Article
</a>
@endsection

@section('content')

<form method="GET" class="mb-3">
  <div class="input-group search-ciel">
    <span class="input-group-text bg-white border-end-0">
      <i class="bi bi-search text-muted"></i>
    </span>
    <input type="text" name="search" class="form-control border-start-0"
           value="{{ request('search') }}" placeholder="Rechercher un article...">
    <button class="btn btn-outline-ciel" type="submit">Rechercher</button>
  </div>
</form>

<div class="table-responsive table-ciel-wrap">
  <table class="table table-custom align-middle mb-0">
    <thead>
      <tr>
        <th class="text-center" width="90">N°</th>
        <th width="120" class="d-none d-md-table-cell">Date</th>
        <th>Désignation</th>
        <th class="d-none d-lg-table-cell">Référence</th>
        <th class="d-none d-md-table-cell">Image</th>
        <th class="text-end">Prix</th>
        <th class="text-center d-none d-lg-table-cell">TVA</th>
        <th class="d-none d-xl-table-cell">Fournisseur</th>
        <th class="text-center">Stock</th>
        <th class="text-center d-none d-lg-table-cell">Seuil</th>
        <th class="text-end" width="140">Actions</th>
      </tr>
    </thead>

    <tbody>
      @forelse($articles as $article)
      <tr class="row-hover">
        <td class="text-center fw-bold text-primary-ciel">
          N°{{ str_pad($article->id, 3, '0', STR_PAD_LEFT) }}
        </td>

        <td class="d-none d-md-table-cell">
          @if($article->date)
            {{ \Carbon\Carbon::parse($article->date)->format('d/m/Y') }}
          @else
            <span class="text-muted">-</span>
          @endif
        </td>

        <td>
          <div class="fw-semibold">{{ $article->designation }}</div>
          <div class="small text-muted d-lg-none">
            {{ $article->reference ?? '' }}
          </div>
        </td>

        <td class="d-none d-lg-table-cell">{{ $article->reference }}</td>

        <td class="d-none d-md-table-cell">
          @if($article->image)
            <img src="{{ asset('storage/'.$article->image) }}" width="46" class="rounded-2">
          @else
            <span class="text-muted">-</span>
          @endif
        </td>

        <td class="text-end">{{ number_format($article->prix,2) }} </td>

        <td class="text-center d-none d-lg-table-cell">{{ $article->tva }}%</td>

        <td class="d-none d-xl-table-cell">
          <span class="badge-soft badge-info-soft">
            {{ $article->fournisseur->nom ?? '-' }}
          </span>
        </td>

        <td class="text-center">
          @php $low = $article->quantite_stock <= $article->seuil_minimum; @endphp
          <span class="badge-soft {{ $low ? 'badge-low' : 'badge-ok' }}">
            {{ $article->quantite_stock }}
          </span>
        </td>

        <td class="text-center d-none d-lg-table-cell">{{ $article->seuil_minimum }}</td>

        <td class="text-end">
          <div class="d-inline-flex gap-1">
            
            <a href="{{ route('articles.edit', $article) }}" class="btn btn-action action-edit" data-bs-toggle="tooltip" title="Modifier">
              <i class="bi bi-pencil-fill"></i>
            </a>
            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button class="btn btn-action action-del btn-delete-confirm" type="button" data-message="Êtes-vous sûr de vouloir supprimer cet article ?" data-bs-toggle="tooltip" title="Supprimer">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="11" class="text-center py-5 text-muted">
          <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
          Aucun article
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection