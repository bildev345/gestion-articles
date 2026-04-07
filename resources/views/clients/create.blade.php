@extends('layout.app')

@section('title', 'Nouveau Client')

@section('actions')
<a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
  <i class="bi bi-arrow-left me-1"></i> Retour
</a>
@endsection

@section('content')

{{-- رسالة duplication --}}


<form action="{{ route('clients.store') }}" method="POST">
  @csrf

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 text-primary-ciel">
            <i class="bi bi-person-vcard me-2"></i> Informations Client
          </h5>
        </div>

        <div class="card-body">
          <div class="row g-3">

            <div class="col-12">
              <label class="form-label">Nom du Client <span class="text-danger">*</span></label>
              <input type="text" name="nom"
                     class="form-control @error('nom') is-invalid @enderror"
                     value="{{ old('nom', session('duplicateData.nom')) }}"
                     placeholder="Ex: Entreprise Dupont SARL" required>
              @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          
            <div class="col-md-6">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', session('duplicateData.email')) }}"
                       placeholder="contact@exemple.com" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Téléphone <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="telephone"
                       class="form-control @error('telephone') is-invalid @enderror"
                       value="{{ old('telephone', session('duplicateData.telephone')) }}"
                       placeholder="Ex: 06 12 34 56 78" required>
                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Date</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                <input type="date" name="date"
                       class="form-control @error('date') is-invalid @enderror"
                       value="{{ old('date', session('duplicateData.date')) }}">
                @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="col-12">
              <label class="form-label">Adresse</label>
              <textarea name="adresse" class="form-control" rows="2"
                        placeholder="Adresse complète...">{{ old('adresse', session('duplicateData.adresse')) }}</textarea>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 text-primary-ciel"><i class="bi bi-gear me-2"></i> Actions</h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-ciel">
              <i class="bi bi-check-lg me-1"></i> Enregistrer
            </button>
            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>
@endsection