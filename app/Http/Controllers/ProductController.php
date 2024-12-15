<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Make sure to import the File facade

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:64000', // Validate the image
    ]);

    $data = $request->all();

    // Handle image upload
    if ($request->hasFile('image')) {
        $imageData = file_get_contents($request->image->getRealPath());
        $data['image'] = base64_encode($imageData); // Encode image data as base64
    }

    Product::create($data);

    return redirect()->route('products.index')->with('success', 'Produit créé avec succès.');
}

public function edit($id)
{
    $product = Product::findOrFail($id); // Retrieve the product or throw a 404 error
    return view('products.edit', compact('product')); // Pass $product to the view
}

    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:64000',
    ]);

    $data = $request->all();

    // Handle image upload
    if ($request->hasFile('image')) {
        $imageData = file_get_contents($request->image->getRealPath());
        $data['image'] = base64_encode($imageData); // Encode new image data as base64
    } else {
        unset($data['image']); // Do not update the image if no new file is uploaded
    }

    $product->update($data);

    return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
}

    public function destroy(Product $product)
    {
        // Delete the image file if it exists
        if ($product->image) {
            File::delete(public_path('images/' . $product->image));
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }
}
