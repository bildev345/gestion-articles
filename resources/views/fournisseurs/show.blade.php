@extends('layout.app')

@section('title', 'Détails du Fournisseur')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Informations du Fournisseur</h3>
        </div>
        <div class="card-body">
          <p><strong>Nom:</strong> {{ $fournisseur->nom }}</p>
          <p><strong>Téléphone:</strong> {{ $fournisseur->telephone }}</p>
          <p><strong>Adresse:</strong> {{ $fournisseur->adresse }}</p>
          <p><strong>Ville:</strong> {{ $fournisseur->ville }}</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection