@extends('layouts.app')

@section('content')
    <h1>Ajouter un Produit</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="name">Nom</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description"></textarea>

        <label for="price">Prix</label>
        <input type="text" name="price" id="price" required>

        <label for="image">Image</label>
        <input type="file" name="image" id="image">

        <button type="submit">Créer Produit</button>
    </form>
@endsection
