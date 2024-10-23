@extends('layouts.app')

@section('content')
    <h1>Modifier le Produit</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="name">Nom</label>
        <input type="text" name="name" id="name" value="{{ $product->name }}" required>

        <label for="description">Description</label>
        <textarea name="description" id="description">{{ $product->description }}</textarea>

        <label for="price">Prix</label>
        <input type="text" name="price" id="price" value="{{ $product->price }}" required>

        <label for="image">Image</label>
        <input type="file" name="image" id="image">

        <!-- If the product already has an image, display it -->
        @if ($product->image)
            <p>Image actuelle :</p>
            <img src="{{ asset('images/' . $product->image) }}" alt="Product Image" style="width:100px;">
        @endif

        <button type="submit">Mettre à jour Produit</button>
    </form>
@endsection
