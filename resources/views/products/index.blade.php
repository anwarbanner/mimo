@extends('layouts.app')

@section('content')
    <h1>Liste des Produits</h1>
    <a href="{{ route('products.create') }}">Ajouter un Produit</a>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        @if ($product->image)
                            <img src="{{ asset('images/' . $product->image) }}" alt="Product Image" style="width:100px;">
                        @else
                            <p>No image available</p>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}">Modifier</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
