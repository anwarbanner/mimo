<?php

namespace App\Http\Controllers;

use App\Models\soin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Make sure to import the File facade

class soinController extends Controller
{
    public function index()
    {
        $soins = soin::all();
        return view('soins.index', compact('soins'));
    }

    public function create()
    {
        return view('soins.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
    ]);

    $data = $request->all();


    soin::create($data);

    return redirect()->route('soins.index')->with('success', 'Produit créé avec succès.');
}

public function edit($id)
{
    $soin = soin::findOrFail($id); // Retrieve the soin or throw a 404 error
    return view('soins.edit', compact('soin')); // Pass $soin to the view
}

    public function update(Request $request, soin $soin)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
    ]);

    $data = $request->all();



    $soin->update($data);

    return redirect()->route('soins.index')->with('success', 'Produit mis à jour avec succès.');
}

    public function destroy(soin $soin)
    {

        $soin->delete();
        return redirect()->route('soins.index')->with('success', 'Produit supprimé avec succès.');
    }
}
