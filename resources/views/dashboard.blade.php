@extends('layout.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <div>
            <h1 class="h3 fw-bold text-primary-ciel mb-0">
                <i class="bi bi-grid-1x2-fill me-2"></i>Dashboard
            </h1>
            <small class="text-muted">Vue générale du système</small>
        </div>

        {{-- LOGOUT BUTTON --}}
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Are you sure you want to logout?')"
                        class="btn btn-danger d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>

    </div>

    @php
        $cards = [
            [
                'label' => 'Articles',
                'value' => $articles_count,
                'route' => route('articles.index'),
                'icon'  => 'bi-box-seam',
                'tone'  => 'primary',
                'footer'=> 'Voir articles'
            ],

            [
                'label' => 'Clients',
                'value' => $clients_count,
                'route' => route('clients.index'),
                'icon'  => 'bi-people',
                'tone'  => 'success',
                'footer'=> 'Voir clients'
            ],

            [
                'label' => 'Fournisseurs',
                'value' => $fournisseurs_count,
                'route' => route('fournisseurs.index'),
                'icon'  => 'bi-truck',
                'tone'  => 'warning',
                'footer'=> 'Voir fournisseurs'
            ],

            [
                'label' => 'Ventes',
                'value' => $ventes_count,
                'route' => route('factures.index'),
                'icon'  => 'bi-cash-stack',
                'tone'  => 'danger',
                'footer'=> 'Voir ventes'
            ],

            [
                'label' => 'Achats',
                'value' => $achats_count,
                'route' => route('fournisseur_factures.index'),
                'icon'  => 'bi-cart',
                'tone'  => 'info',
                'footer'=> 'Voir achats'
            ],

            [
                'label' => 'Stock Faible',
                'value' => $faible_stock,
                'route' => route('stock.index'),
                'icon'  => 'bi-exclamation-triangle',
                'tone'  => 'warning',
                'footer'=> 'Vérifier'
            ],

            [
                'label' => 'Rupture Stock',
                'value' => $rupture_stock,
                'route' => route('stock.index'),
                'icon'  => 'bi-x-circle',
                'tone'  => 'danger',
                'footer'=> 'Urgent'
            ],
        ];
    @endphp

    {{-- CARDS GRID --}}
    <div class="row g-4 mb-4">
        @foreach($cards as $card)
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ $card['route'] }}" class="text-decoration-none">
                    <div class="card dash-card h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase fw-bold mb-1">
                                    {{ $card['label'] }}
                                </p>

                                <h3 class="fw-bold mb-0 text-dark">
                                    {{ $card['value'] }}
                                </h3>
                            </div>

                            <div class="icon-box tone-{{ $card['tone'] }}">
                                <i class="bi {{ $card['icon'] }} fs-4"></i>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                            <span class="small footer-link tone-text-{{ $card['tone'] }} fw-bold">
                                {{ $card['footer'] }} <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>

                        <div class="card-glow tone-border-{{ $card['tone'] }}"></div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</div>

@endsection

@push('styles')
<style>
    body{
        background: linear-gradient(135deg, #f8fafc, #eef2ff);
    }

    .text-primary-ciel{
        color: #4e73df;
    }

    .dash-card{
        position: relative;
        border: 1px solid rgba(255,255,255,0.45);
        border-radius: 20px;
        background: rgba(255,255,255,0.78);
        backdrop-filter: blur(12px);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        transition: all .35s ease;
        overflow: hidden;
    }

    .dash-card:hover{
        transform: translateY(-8px) scale(1.015);
        box-shadow: 0 22px 45px rgba(15, 23, 42, 0.16);
    }

    .card-glow{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .icon-box{
        width: 62px;
        height: 62px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        transition: all .3s ease;
    }

    .dash-card:hover .icon-box{
        transform: rotate(8deg) scale(1.08);
    }

    .footer-link{
        transition: all .3s ease;
    }

    .dash-card:hover .footer-link{
        letter-spacing: 0.2px;
    }

    .tone-primary{ background: linear-gradient(135deg, #4e73df, #224abe); }
    .tone-success{ background: linear-gradient(135deg, #1cc88a, #13855c); }
    .tone-warning{ background: linear-gradient(135deg, #f6c23e, #dda20a); }
    .tone-danger{ background: linear-gradient(135deg, #e74a3b, #be2617); }
    .tone-info{ background: linear-gradient(135deg, #36b9cc, #258391); }

    .tone-text-primary{ color: #224abe; }
    .tone-text-success{ color: #13855c; }
    .tone-text-warning{ color: #b78103; }
    .tone-text-danger{ color: #be2617; }
    .tone-text-info{ color: #258391; }

    .tone-border-primary{ background: linear-gradient(90deg, #4e73df, #224abe); }
    .tone-border-success{ background: linear-gradient(90deg, #1cc88a, #13855c); }
    .tone-border-warning{ background: linear-gradient(90deg, #f6c23e, #dda20a); }
    .tone-border-danger{ background: linear-gradient(90deg, #e74a3b, #be2617); }
    .tone-border-info{ background: linear-gradient(90deg, #36b9cc, #258391); }
</style>
@endpush