@extends('layout.app')
@section('title', 'Gestion des Clients')

@section('actions')
    <a href="{{ route('clients.create') }}" class="btn btn-ciel">
        <i class="bi bi-person-plus me-1"></i> Nouveau Client
    </a>
<a href="{{ route('clients.situation') }}" class="btn btn-warning ms-2" title="Reste Factures à payé">
    <i class="bi bi-info-circle"></i>
</a>

<a href="{{ route('clients.reste') }}" class="btn btn-danger ms-2" title="Reste devis à payé">
    <i class="bi bi-cash-coin"></i>
</a>
@endsection

@section('content')

    {{-- Search --}}
    <form method="GET" class="mb-3">
        <div class="input-group search-ciel">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control border-start-0"
                   placeholder="Rechercher un client...">
            <button class="btn btn-outline-ciel" type="submit">
                Rechercher
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive table-ciel-wrap">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th width="90" class="text-center">N°</th>
                    <th width="120" class="d-none d-md-table-cell">Date</th>
                    <th>Nom du Client</th>
                    <th>Contact</th>
                    <th class="d-none d-lg-table-cell">Email</th>
                    <th class="d-none d-xl-table-cell">Adresse</th>
                    <th width="140" class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clients as $client)
                    <tr class="row-hover">
                        <td class="text-center fw-bold text-primary-ciel">
                            N°{{ str_pad($client->id, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="d-none d-md-table-cell">
                            @if($client->date)
                                {{ \Carbon\Carbon::parse($client->date)->format('d/m/Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $client->nom }}</div>
                            <div class="small text-muted d-md-none">
                                {{-- Mobile mini info --}}
                                @if($client->email)
                                    <i class="bi bi-envelope me-1"></i>{{ $client->email }}
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($client->telephone)
                                <a href="tel:{{ $client->telephone }}" class="link-ciel text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>{{ $client->telephone }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td class="d-none d-lg-table-cell">
                            @if($client->email)
                                <a href="mailto:{{ $client->email }}" class="link-ciel text-decoration-none">
                                    <i class="bi bi-envelope me-1"></i>{{ $client->email }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td class="d-none d-xl-table-cell">
                            <span class="text-muted">{{ $client->adresse ?? 'N/A' }}</span>
                        </td>

                        <td class="text-end">
                            <div class="d-inline-flex gap-1">

                                

                                <a href="{{ route('clients.edit', $client->id) }}"
                                   class="btn btn-action action-edit"
                                   data-bs-toggle="tooltip" title="Modifier">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-action action-del btn-delete-confirm"
                                            type="button"
                                            data-message="Êtes-vous sûr de vouloir supprimer ce client ?"
                                            data-bs-toggle="tooltip" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                <span class="fs-5">Aucun client trouvé</span>
                                <p class="small mb-0">Commencez par ajouter un nouveau client</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
       
    </div>

@endsection
