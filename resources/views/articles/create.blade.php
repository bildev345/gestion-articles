@extends('layout.app')

@section('title', 'Créer Article')

@section('actions')
<a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
  <i class="bi bi-arrow-left me-1"></i> Retour
</a>
@endsection

@section('content')

<form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
  @csrf

  @if(session('message'))
    <div class="alert alert-info">{{ session('message') }}</div>
  @endif

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 text-primary-ciel">
            <i class="bi bi-box-seam me-2"></i> Informations Article
          </h5>
        </div>

        <div class="card-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Désignation <span class="text-danger">*</span></label>
              <input type="text" name="designation"
                     class="form-control @error('designation') is-invalid @enderror"
                     value="{{ old('designation', session('duplicateData.designation')) }}"
                     placeholder="Ex: Article Alpha" required>
              @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Référence <span class="text-danger">*</span></label>
              <input type="text" name="reference"
                     class="form-control @error('reference') is-invalid @enderror"
                     value="{{ old('reference', session('duplicateData.reference')) }}"
                     placeholder="Ex: REF-2024-001" required>
              @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Image</label>
              <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
              @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

            <div class="col-md-6">
              <label class="form-label">Prix <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">DH</span>
                <input type="number" step="0.01" name="prix"
                       class="form-control @error('prix') is-invalid @enderror"
                       value="{{ old('prix', session('duplicateData.prix')) }}" required>
                @error('prix') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">TVA (%) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-percent"></i></span>
                <input type="number" name="tva"
                       class="form-control @error('tva') is-invalid @enderror"
                       value="{{ old('tva', session('duplicateData.tva')) }}" required>
                @error('tva') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Stock Initial</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                <input type="number" name="quantite_stock" class="form-control"
                       value="{{ old('quantite_stock', session('duplicateData.quantite_stock', 0)) }}">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Seuil Minimum</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-exclamation-triangle"></i></span>
                <input type="number" name="seuil_minimum" class="form-control"
                       value="{{ old('seuil_minimum', session('duplicateData.seuil_minimum', 0)) }}">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Fournisseur <span class="text-danger">*</span></label>
              <select name="fournisseur_id" class="form-select @error('fournisseur_id') is-invalid @enderror" required>
                <option value="">Choisir fournisseur</option>
                @foreach($fournisseurs as $f)
                  <option value="{{ $f->id }}" {{ (old('fournisseur_id') ?? session('duplicateData.fournisseur_id')) == $f->id ? 'selected' : '' }}>
                    {{ $f->nom }}
                  </option>
                @endforeach
              </select>
              @error('fournisseur_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"
                        placeholder="Description détaillée...">{{ old('description', session('duplicateData.description')) }}</textarea>
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
            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </div>
      </div>
    </div>
  </div>

</form>
@endsection