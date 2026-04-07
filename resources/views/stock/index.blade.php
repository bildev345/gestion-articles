@extends('layout.app')

@section('title', 'Gestion de Stock')

@section('content')

@php
    // ✅ FIX: correct low stock calculation
    $lowStockCount = $articles->filter(function ($article) {
        return $article->quantite_stock <= $article->seuil_minimum;
    })->count();
@endphp

<div class="container">

    {{-- 🔴 ALERT GLOBAL --}}
    @if($lowStockCount > 0)
        <div class="alert alert-danger mb-3">
            ⚠️ Attention ! {{ $lowStockCount }} article(s) avec stock faible
        </div>
    @endif

    {{-- 📊 BADGE --}}
    <div class="mb-3">
        <span class="badge bg-danger">
            Stock faible: {{ $lowStockCount }}
        </span>
    </div>
    <form method="GET" action="{{ route('stock.index') }}" class="mb-3">
    <div class="input-group">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="🔍 Rechercher un article..."
            value="{{ request('search') }}"
        >

        <button class="btn btn-primary" type="submit">
            Rechercher
        </button>

        @if(request('search'))
            <a href="{{ route('stock.index') }}" class="btn btn-secondary">
                Reset
            </a>
        @endif
    </div>
</form>

    <div class="table-responsive table-ciel-wrap">
        <table class="table table-custom align-middle mb-0">

            <thead>
                <tr>
                    <th>Article</th>
                    <th class="text-center">Stock Actuel</th>
                    <th class="text-center d-none d-md-table-cell">Seuil Minimum</th>
                    <th class="text-center">Statut</th>
                </tr>
            </thead>

            <tbody>

                @forelse($articles as $article)

                    @php
                        $isLow = $article->quantite_stock <= $article->seuil_minimum;
                    @endphp

                    <tr class="row-hover {{ $isLow ? 'table-danger' : '' }}">

                        <td class="fw-semibold">
                            {{ $article->designation }}
                        </td>

                        <td class="text-center">
                            <span class="badge-soft {{ $isLow ? 'badge-low' : 'badge-ok' }}">
                                {{ $article->quantite_stock }}
                            </span>
                        </td>

                        <td class="text-center d-none d-md-table-cell">
                            {{ $article->seuil_minimum }}
                        </td>

                        <td class="text-center">
                            @if($isLow)
                                <span class="badge-soft badge-low">⚠️ Faible Stock</span>
                            @else
                                <span class="badge-soft badge-ok">✔️ OK</span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-boxes fs-1 d-block mb-2"></i>
                            Aucun article disponible
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection