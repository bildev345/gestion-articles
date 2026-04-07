@extends('layout.app')

@section('title', 'Nouveau Fournisseur')

@section('actions')
    <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
@endsection

@section('content')

{{-- رسالة duplication --}}
@if(session('message'))
  <div class="alert alert-info">
    <i class="bi bi-info-circle me-1"></i> {{ session('message') }}
  </div>
@endif

<form action="{{ route('fournisseurs.store') }}" method="POST">
    @csrf
    
    <div class="row g-4">
        <!-- Form Card -->
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
                        <div class="col-md-12">
                            <label for="nom" class="form-label">
                                Nom du Fournisseur <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   id="nom"
                                   name="nom"
                                   value="{{ old('nom', session('duplicateData.nom')) }}"
                                   placeholder="Ex: Entreprise Dupont SARL"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label for="date" class="form-label">
                                Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date"
                                       class="form-control @error('date') is-invalid @enderror"
                                       id="date"
                                       name="date"
                                       value="{{ old('date', session('duplicateData.date')) }}"
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">
                                Téléphone <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text"
                                       class="form-control @error('telephone') is-invalid @enderror"
                                       id="telephone"
                                       name="telephone"
                                       value="{{ old('telephone', session('duplicateData.telephone')) }}"
                                       placeholder="Ex: 0522 123 456"
                                       required>
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ville -->
                        <div class="col-md-12">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text"
                                   class="form-control @error('ville') is-invalid @enderror"
                                   id="ville"
                                   name="ville"
                                   value="{{ old('ville', session('duplicateData.ville')) }}"
                                   placeholder="Ville...">
                            @error('ville')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-12">
                            <label for="adresse" class="form-label">Adresse</label>
                            <textarea class="form-control"
                                      id="adresse"
                                      name="adresse"
                                      rows="2"
                                      placeholder="Adresse complète...">{{ old('adresse', session('duplicateData.adresse')) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-ciel">
                        <i class="bi bi-gear me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-ciel">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
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

@endsection