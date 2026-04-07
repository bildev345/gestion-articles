@extends('layout.app')

@section('title', 'Modifier Article')

@section('actions')
    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
@endsection

@section('content')

<form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-ciel">
                        <i class="bi bi-box-seam me-2"></i>Informations Article
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        
                        <!-- Désignation -->
                        <div class="col-md-6">
                            <label for="designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('designation') is-invalid @enderror" 
                                   id="designation" 
                                   name="designation" 
                                   value="{{ old('designation', $article->designation) }}" 
                                   placeholder="Ex: Article Alpha" 
                                   required>
                            @error('designation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Référence -->
                        <div class="col-md-6">
                            <label for="reference" class="form-label">Référence <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('reference') is-invalid @enderror" 
                                   id="reference" 
                                   name="reference" 
                                   value="{{ old('reference', $article->reference) }}" 
                                   placeholder="Ex: REF-2024-001" 
                                   required>
                            @error('reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="col-md-6">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if($article->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$article->image) }}" alt="Image actuelle" width="80" class="img-thumbnail">
                                <small class="text-muted d-block">Image actuelle</small>
                            </div>
                            @endif
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       id="date" 
                                       name="date" 
                                       value="{{ old('date', $article->date) }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="col-md-6">
                            <label for="prix" class="form-label">Prix <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control @error('prix') is-invalid @enderror" 
                                       id="prix" 
                                       name="prix" 
                                       value="{{ old('prix', $article->prix) }}" 
                                       required>
                                @error('prix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- TVA -->
                        <div class="col-md-4">
                            <label for="tva" class="form-label">TVA (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-percent"></i></span>
                                <input type="number" 
                                       class="form-control @error('tva') is-invalid @enderror" 
                                       id="tva" 
                                       name="tva" 
                                       value="{{ old('tva', $article->tva) }}" 
                                       required>
                                @error('tva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Stock Initial -->
                        <div class="col-md-4">
                            <label for="quantite_stock" class="form-label">Stock</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                <input type="number" 
                                       class="form-control" 
                                       id="quantite_stock" 
                                       name="quantite_stock" 
                                       value="{{ old('quantite_stock', $article->quantite_stock) }}">
                            </div>
                        </div>

                        <!-- Seuil Minimum -->
                        <div class="col-md-4">
                            <label for="seuil_minimum" class="form-label">Seuil Min.</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-exclamation-triangle"></i></span>
                                <input type="number" 
                                       class="form-control" 
                                       id="seuil_minimum" 
                                       name="seuil_minimum" 
                                       value="{{ old('seuil_minimum', $article->seuil_minimum) }}">
                            </div>
                        </div>

                        <!-- Fournisseur -->
                        <div class="col-md-6">
                            <label for="fournisseur_id" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                            <select class="form-select @error('fournisseur_id') is-invalid @enderror" 
                                    id="fournisseur_id" 
                                    name="fournisseur_id" 
                                    required>
                                <option value="">Choisir fournisseur</option>
                                @foreach($fournisseurs as $f)
                                    <option value="{{ $f->id }}" {{ old('fournisseur_id', $article->fournisseur_id) == $f->id ? 'selected' : '' }}>
                                        {{ $f->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fournisseur_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Description détaillée...">{{ old('description', $article->description) }}</textarea>
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
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection