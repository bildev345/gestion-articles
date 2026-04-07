@extends('layout.app')

@section('title', 'Modifier Fournisseur')

@section('actions')
    <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
@endsection

@section('content')

<div class="container-fluid">
<form action="{{ route('fournisseurs.update', $fournisseur->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-ciel">
                        <i class="bi bi-truck me-2"></i>Informations Fournisseur
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        
                        <!-- Nom -->
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom du Fournisseur <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $fournisseur->nom) }}" 
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $fournisseur->date) }}">
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="{{ old('telephone', $fournisseur->telephone) }}">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $fournisseur->email) }}">
                            </div>
                        </div>

                        <!-- Ville -->
                        <div class="col-md-6">
                            <label for="ville" class="form-label">Ville</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="ville" 
                                       name="ville" 
                                       value="{{ old('ville', $fournisseur->ville) }}">
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-6">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="adresse" 
                                   name="adresse" 
                                   value="{{ old('adresse', $fournisseur->adresse) }}">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Actions Column -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-ciel">
                        <i class="bi bi-gear me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-ciel">
                            <i class="bi bi-check-lg me-1"></i> Mettre à jour
                        </button>
                        <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>

@endsection