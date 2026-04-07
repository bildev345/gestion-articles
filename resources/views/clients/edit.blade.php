@extends('layout.app')

@section('title', 'Modifier Client')

@section('actions')
    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
@endsection

@section('content')

    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary-ciel">
                            <i class="bi bi-person-vcard me-2"></i>Informations Client
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            
                            <!-- Nom -->
                            <div class="col-md-12">
                                <label for="nom" class="form-label">Nom du Client <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom', $client->nom) }}" 
                                       placeholder="Ex: Entreprise Dupont SARL"
                                       required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type Client -->
                            <div class="col-md-6">
                                <label for="type" class="form-label">Type de Client</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="particulier" {{ old('type', $client->type ?? 'particulier') == 'particulier' ? 'selected' : '' }}>Particulier</option>
                                    <option value="entreprise" {{ old('type', $client->type ?? '') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                                    <option value="professionnel" {{ old('type', $client->type ?? '') == 'professionnel' ? 'selected' : '' }}>Professionnel</option>
                                </select>
                            </div>

                            <!-- ICE -->
                            <div class="col-md-6">
                                <label for="ice" class="form-label">ICE</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="ice" 
                                       name="ice" 
                                       value="{{ old('ice', $client->ice ?? '') }}" 
                                       placeholder="Ex: 001234567890009">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $client->email) }}" 
                                           placeholder="contact@exemple.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Téléphone -->
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" 
                                           class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" 
                                           name="telephone" 
                                           value="{{ old('telephone', $client->telephone) }}" 
                                           placeholder="Ex: 0522 123 456"
                                           required>
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                           value="{{ old('date', $client->date) }}">
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div class="col-md-12">
                                <label for="adresse" class="form-label">Adresse</label>
                                <textarea class="form-control" 
                                          id="adresse" 
                                          name="adresse" 
                                          rows="2" 
                                          placeholder="Adresse complète...">{{ old('adresse', $client->adresse ?? '') }}</textarea>
                            </div>

                            <!-- Ville -->
                            <div class="col-md-6">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="ville" 
                                       name="ville" 
                                       value="{{ old('ville', $client->ville ?? '') }}" 
                                       placeholder="Ex: Casablanca">
                            </div>

                            <!-- Code Postal -->
                            <div class="col-md-6">
                                <label for="code_postal" class="form-label">Code Postal</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="code_postal" 
                                       name="code_postal" 
                                       value="{{ old('code_postal', $client->code_postal ?? '') }}" 
                                       placeholder="Ex: 20000">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Card -->
            <div class="col-lg-4">
                <!-- Notes Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary-ciel">
                            <i class="bi bi-card-text me-2"></i>Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <label for="notes" class="form-label">Notes supplémentaires</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="5" 
                                      placeholder="Informations complémentaires...">{{ old('notes', $client->notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-ciel">
                                <i class="bi bi-check-lg me-1"></i> Mettre à jour
                            </button>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection