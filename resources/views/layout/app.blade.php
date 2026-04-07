<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mini CIEL System</title>

  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

  @stack('styles')
  @livewireStyles
  <style>
    :root{
      --ciel-primary:#2f5597;
      --ciel-primary-dark:#1e3d6b;
      --ciel-primary-light:#4b7bc7;

      --ciel-bg:#f4f6f9;
      --ciel-surface:#ffffff;

      --ciel-text:#0f172a;
      --ciel-muted:rgba(15,23,42,.62);

      --ciel-border:rgba(15,23,42,.10);
      --ciel-border-soft:rgba(15,23,42,.06);

      --ciel-radius:16px;
      --ciel-radius-sm:12px;

      --ciel-shadow:0 10px 30px rgba(15,23,42,.08);
      --ciel-shadow-soft:0 6px 18px rgba(15,23,42,.06);
      --ciel-shadow-hover:0 18px 50px rgba(47,85,151,.16);
    }

    body{
      background:
        radial-gradient(900px 450px at 15% 0%, rgba(47,85,151,.10), transparent 60%),
        radial-gradient(900px 450px at 85% 0%, rgba(13,202,240,.08), transparent 55%),
        var(--ciel-bg);
      font-family:"Segoe UI","Segoe UI Web (West European)",-apple-system,BlinkMacSystemFont,Roboto,"Helvetica Neue",sans-serif;
      color:var(--ciel-text);
      font-size:.95rem;
    }

    /* ===== NAVBAR (modern) ===== */
    .navbar-ciel{
      background: linear-gradient(90deg, var(--ciel-primary), var(--ciel-primary-dark));
      padding:.9rem 1.4rem;
      box-shadow: 0 10px 25px rgba(47,85,151,.18);
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid rgba(255,255,255,.12);
    }

    .navbar-ciel .navbar-brand{
      font-weight:800;
      font-size:1.15rem;
      letter-spacing:.4px;
      display:flex;
      align-items:center;
      gap:10px;
      color:#fff !important;
    }

    .navbar-ciel .nav-link{
      color: rgba(255,255,255,.86) !important;
      font-weight:600;
      padding:.55rem .9rem !important;
      border-radius: 999px;
      transition: background .2s ease, transform .2s ease, color .2s ease;
      margin-right:6px;
      display:flex;
      align-items:center;
      gap:8px;
    }

    .navbar-ciel .nav-link:hover{
      background: rgba(255,255,255,.12);
      color:#fff !important;
      transform: translateY(-1px);
    }

    .navbar-ciel .nav-link.active{
      background: rgba(255,255,255,.18);
      color:#fff !important;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.18);
    }

    .navbar-toggler{
      border-color: rgba(255,255,255,.35);
    }
    .navbar-toggler:focus{
      box-shadow: 0 0 0 .2rem rgba(255,255,255,.15);
    }
    .navbar-toggler-icon{
      background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* ===== WORKSPACE (SaaS card) ===== */
    .workspace{
      background: var(--ciel-surface);
      border-radius: var(--ciel-radius);
      box-shadow: var(--ciel-shadow);
      border: 1px solid var(--ciel-border-soft);
      margin: 22px 0 44px;
      overflow: hidden;
    }

    .workspace-header{
      padding: 1.25rem 1.6rem;
      border-bottom: 1px solid var(--ciel-border-soft);
      display:flex;
      justify-content:space-between;
      align-items:center;
      background:
        linear-gradient(180deg, rgba(47,85,151,.05), transparent 60%),
        #fff;
    }

    .workspace-title{
      font-size: 1.25rem;
      font-weight: 800;
      color: var(--ciel-primary);
      margin:0;
      display:flex;
      align-items:center;
      gap:10px;
    }

    .workspace-content{
      padding: 1.5rem;
    }

    /* ===== BUTTONS ===== */
    .btn-ciel{
      background: linear-gradient(180deg, var(--ciel-primary-light), var(--ciel-primary));
      color:#fff;
      border: 1px solid rgba(255,255,255,.0);
      padding:.55rem 1.1rem;
      font-weight:700;
      border-radius: 12px;
      transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }

    .btn-ciel:hover{
      color:#fff;
      transform: translateY(-2px);
      box-shadow: 0 12px 26px rgba(47,85,151,.25);
      filter: brightness(1.02);
    }

    .btn-outline-ciel{
      border:1px solid rgba(47,85,151,.35);
      color: var(--ciel-primary);
      background: rgba(47,85,151,.04);
      border-radius: 12px;
      font-weight:700;
      padding:.55rem 1.1rem;
      transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .btn-outline-ciel:hover{
      background: rgba(47,85,151,.10);
      transform: translateY(-2px);
      box-shadow: var(--ciel-shadow-soft);
    }

    /* ===== FORMS ===== */
    .form-control, .form-select{
      border-radius: 12px;
      border-color: var(--ciel-border-soft);
    }

    .form-control:focus, .form-select:focus{
      border-color: rgba(47,85,151,.35);
      box-shadow: 0 0 0 .2rem rgba(47,85,151,.12);
    }

    .form-label{
      font-weight:700;
      color: rgba(15,23,42,.78);
      margin-bottom:.4rem;
    }

    /* ===== TABLES (clean) ===== */
    .table-custom{
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
      margin-bottom: 1rem;
      overflow:hidden;
      border-radius: 14px;
      border: 1px solid var(--ciel-border-soft);
    }

    .table-custom thead th{
      background: #f8fafc;
      color: rgba(15,23,42,.55);
      font-weight:800;
      text-transform: uppercase;
      font-size: .72rem;
      letter-spacing: .08em;
      padding: .95rem 1rem;
      border-bottom: 1px solid var(--ciel-border-soft);
      vertical-align: middle;
    }

    .table-custom tbody tr{
      background:#fff;
      transition: background-color .15s ease;
    }

    .table-custom tbody tr:hover{
      background: rgba(47,85,151,.04);
    }

    .table-custom td{
      padding: .95rem 1rem;
      vertical-align: middle;
      border-bottom: 1px solid var(--ciel-border-soft);
      color: rgba(15,23,42,.86);
    }

    /* ===== Pagination ===== */
    .pagination{
      display:flex;
      justify-content:center !important;
      gap:6px;
    }

    .pagination .page-link{
      border-radius: 12px !important;
      border-color: var(--ciel-border-soft) !important;
      color: rgba(15,23,42,.75);
    }

    .pagination .page-item.active .page-link{
      background: var(--ciel-primary) !important;
      border-color: var(--ciel-primary) !important;
      color:#fff !important;
    }

    /* ===== Utilities ===== */
    .text-primary-ciel{ color: var(--ciel-primary) !important; }
    .shadow-sm-custom{ box-shadow: var(--ciel-shadow-soft) !important; }

    /* ===== Dashboard helpers (so it matches) ===== */
    .dash-card{
      border-radius: 16px;
      border: 1px solid var(--ciel-border-soft);
      background: linear-gradient(180deg, #fff, #fbfcfe);
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
      overflow:hidden;
      position:relative;
    }
    .dash-card:hover{
      transform: translateY(-7px);
      box-shadow: var(--ciel-shadow-hover);
      border-color: rgba(47,85,151,.28);
    }
    .dash-card:hover .icon-box{
      transform: scale(1.08);
      box-shadow: 0 10px 26px rgba(47,85,151,.18);
    }

    .icon-box{
      width: 56px;
      height: 56px;
      border-radius: 14px;
      display:grid;
      place-items:center;
      border: 1px solid rgba(15,23,42,.06);
      transition: transform .25s ease, box-shadow .25s ease;
    }

    .tone-primary{ background: rgba(47,85,151,.12); color: var(--ciel-primary); }
    .tone-success{ background: rgba(25,135,84,.12); color: #198754; }
    .tone-warning{ background: rgba(255,193,7,.18); color: #b78103; }
    .tone-danger { background: rgba(220,53,69,.12); color: #dc3545; }
    .tone-info   { background: rgba(13,202,240,.14); color: #0dcaf0; }
    .tone-dark   { background: rgba(33,37,41,.10); color: #212529; }

    /* optional top accent for cards */
    .tone-top-primary{ box-shadow: inset 0 3px 0 rgba(47,85,151,.85); }
    .tone-top-success{ box-shadow: inset 0 3px 0 rgba(25,135,84,.85); }
    .tone-top-warning{ box-shadow: inset 0 3px 0 rgba(255,193,7,.90); }
    .tone-top-danger { box-shadow: inset 0 3px 0 rgba(220,53,69,.85); }
    .tone-top-info   { box-shadow: inset 0 3px 0 rgba(13,202,240,.85); }
    .tone-top-dark   { box-shadow: inset 0 3px 0 rgba(33,37,41,.70); }

    @media (max-width: 768px){
      .workspace-content{ padding: 1.1rem; }
      .navbar-ciel{ padding:.85rem 1rem; }
      .icon-box{ width:52px; height:52px; border-radius: 12px; }
    }
    /* =========================
   RESPONSIVE (PHONE/TABLET)
   ========================= */

/* 1) Phones */
@media (max-width: 576px){

  /* container padding */
  .container, .container-fluid{
    padding-left: 12px !important;
    padding-right: 12px !important;
  }

  /* navbar */
  .navbar-ciel{
    padding: .75rem 1rem;
  }

  .navbar-ciel .navbar-brand{
    font-size: 1.05rem;
  }

  .navbar-ciel .nav-link{
    margin-right: 0;
    border-radius: 12px;
    padding: .6rem .8rem !important;
  }

  /* workspace */
  .workspace{
    margin: 14px 0 28px;
    border-radius: 14px;
  }

  .workspace-header{
    padding: 1rem 1rem;
    flex-direction: column;
    align-items: flex-start;
    gap: .75rem;
  }

  .workspace-title{
    font-size: 1.1rem;
  }

  .workspace-content{
    padding: 1rem;
  }

  /* buttons full width on mobile (optional) */
  .workspace-actions .btn{
    width: 100%;
  }

  /* dashboard cards */
  .dash-card{
    border-radius: 14px;
  }

  .icon-box{
    width: 48px;
    height: 48px;
    border-radius: 12px;
  }

  /* reduce big headings */
  h1.h3{ font-size: 1.25rem; }
  h3{ font-size: 1.45rem; }

  /* tables: allow horizontal scroll */
  .table-responsive{
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .table-custom{
    min-width: 720px; /* باش ما يتكسرش الجدول */
  }
}

/* 2) Small tablets */
@media (min-width: 577px) and (max-width: 992px){

  .workspace-content{
    padding: 1.25rem;
  }

  .workspace-header{
    padding: 1.1rem 1.25rem;
  }

  .navbar-ciel{
    padding: .85rem 1.2rem;
  }


}
@media (max-width: 576px){
  .table-responsive{
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 14px;
  }

  /* خلي الجدول ما يتكسرش ويولي قابل للتمرير */
  .table-custom{
    min-width: 900px; /* زيدها حسب عدد الأعمدة */
  }
}

/* ===== Shared UI for all index pages ===== */
.table-ciel-wrap{
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid rgba(15,23,42,.06);
  background: #fff;
}

.row-hover{ transition: background .15s ease; }
.row-hover:hover{ background: rgba(47,85,151,.04); }

.search-ciel .input-group-text{ border-radius: 12px 0 0 12px; }
.search-ciel .form-control{ border-radius: 0; }
.search-ciel .btn{ border-radius: 0 12px 12px 0; }

.link-ciel{ color: var(--ciel-primary); }
.link-ciel:hover{ color: var(--ciel-primary-dark); text-decoration: underline; }

/* action buttons unified */
.btn-action{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  width:36px;
  height:36px;
  padding:0;
  border-radius: 12px;
  border: 1px solid rgba(15,23,42,.10);
  background:#fff;
  transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, color .15s ease;
}
.btn-action:hover{
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(15,23,42,.10);
}

/* actions colors */
.action-view{
  color: var(--ciel-primary);
  border-color: rgba(47,85,151,.28);
  background: rgba(47,85,151,.06);
}
.action-view:hover{ background: rgba(47,85,151,.12); }

.action-edit{
  color: #198754;
  border-color: rgba(25,135,84,.28);
  background: rgba(25,135,84,.06);
}
.action-edit:hover{ background: rgba(25,135,84,.12); }

.action-dup{
  color: #0dcaf0;
  border-color: rgba(13,202,240,.28);
  background: rgba(13,202,240,.08);
}
.action-dup:hover{ background: rgba(13,202,240,.14); }

.action-del{
  color: #dc3545;
  border-color: rgba(220,53,69,.28);
  background: rgba(220,53,69,.06);
}
.action-del:hover{ background: rgba(220,53,69,.12); }

/* stock badges */
.badge-soft{
  border-radius: 999px;
  font-weight: 700;
  padding: .35rem .6rem;
}
.badge-ok{ background: rgba(25,135,84,.12); color:#198754; border:1px solid rgba(25,135,84,.20); }
.badge-low{ background: rgba(220,53,69,.12); color:#dc3545; border:1px solid rgba(220,53,69,.20); }
.badge-info-soft{ background: rgba(13,202,240,.14); color:#0aa2c0; border:1px solid rgba(13,202,240,.18); }

/* mobile: table readable */
@media (max-width: 576px){
  .table-custom{ min-width: 900px; }
}
  </style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-ciel">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="{{ url('/') }}">
      <i class="bi bi-cloud-fill"></i> ECO CIEL
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
            <i class="bi bi-people-fill"></i> Clients
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
            <i class="bi bi-box-seam-fill"></i> Articles
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('factures.*') ? 'active' : '' }}" href="{{ route('factures.index') }}">
            <i class="bi bi-file-earmark-text-fill"></i> Facture
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('devis.*') ? 'active' : '' }}" href="{{ route('devis.index') }}">
            <i class="bi bi-file-earmark-text-fill"></i> Devis
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}" href="{{ route('fournisseurs.index') }}">
            <i class="bi bi-truck"></i> Fournisseurs
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('fournisseur_factures.*') ? 'active' : '' }}" href="{{ route('fournisseur_factures.index') }}">
            <i class="bi bi-cart-check-fill"></i> Achats
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}" href="{{ route('stock.index') }}">
            <i class="bi bi-boxes"></i> Stock
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- ===== CONTENT ===== -->
<div class="container">
  <div class="workspace">

    <div class="workspace-header">
      <h2 class="workspace-title">
        @yield('title', 'Dashboard')
      </h2>
      <div class="workspace-actions">
        @yield('actions')
      </div>
    </div>

    <div class="workspace-content">
      @yield('content')
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  document.querySelectorAll('.btn-delete-confirm').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const form = this.closest('form');
      const message = this.dataset.message || 'Êtes-vous sûr ?';

      Swal.fire({
        title: 'Confirmation',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });

  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Succès',
      text: {!! json_encode(session('success')) !!},
      timer: 3000,
      timerProgressBar: true
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Erreur',
      text: {!! json_encode(session('error')) !!}
    });
  @endif
})
</script>
@livewireScripts
</body>
</html>