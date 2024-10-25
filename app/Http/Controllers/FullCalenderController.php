<?php

namespace App\Http\Controllers;

use App\Models\Rdv;
use Illuminate\Http\Request;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Rdv::whereDate('heure_debut', '>=', $request->start)
                        ->whereDate('heure_fin', '<=', $request->end)
                        ->get(['id', 'motif as title', 'heure_debut as start', 'heure_fin as end', 'etat']); // Include necessary fields

            return response()->json($data);
        }
        return view('rdvs.index'); // Ensure you have this view
    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = Rdv::create([
                    'patient_id' => $request->patient_id, // Make sure to pass this in the request
                    'motif' => $request->title, // Assuming 'title' is used for the motif
                    'date' => $request->date, // Include this if you're passing a date
                    'heure_debut' => $request->heure_debut,
                    'heure_fin' => $request->heure_fin,
                    'etat' => $request->etat, // Optional
                ]);

                return response()->json($event);

            case 'update':
                $event = Rdv::find($request->id);
                $event->update([
                    'motif' => $request->title, // Assuming 'title' is the motif
                    'heure_debut' => $request->heure_debut,
                    'heure_fin' => $request->heure_fin,
                    'etat' => $request->etat, // Optional
                ]);

                return response()->json($event);

            case 'delete':
                $event = Rdv::find($request->id);
                $event->delete();

                return response()->json(['success' => true]);

            default:
                return response()->json(['success' => false, 'message' => 'Invalid request type.']);
        }
    }
}
