<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\VisiteImage;


class VisiteImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'visite_id' => 'required|exists:visites,id',
            'images' => 'required|image',
            'description' => 'nullable|string',
        ]);

        $visiteId = $request->input('visite_id');
        $imageCount = VisiteImage::where('visite_id', $visiteId)->count();

        if ($imageCount >= 4) {
            return response()->json(['error' => 'Vous pouvez ajouter 4 images maximum pour chaque visite.'], 400);
        }

        $visiteImage = new VisiteImage();
        $visiteImage->visite_id = $visiteId;
        $visiteImage->images = $request->file('images')->store('images');
        $visiteImage->description = $request->input('description');
        $visiteImage->save();

        return response()->json(['success' => 'Image ajoutée avec succès.']);
    }
    public function destroy($id)
    {
        $visiteImage = VisiteImage::find($id);

        if (!$visiteImage) {
            return response()->json(['error' => 'Image not found.'], 404);
        }

        $visiteImage->delete();

        return response()->json(['success' => 'Image deleted successfully.']);
    }
}