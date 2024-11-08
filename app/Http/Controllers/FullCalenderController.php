<?php
namespace App\Http\Controllers;

use App\Models\Rdv;
use Illuminate\Http\Request;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Rdv::with('patient') // Eager load patient relation
                        ->whereDate('start', '>=', $request->start)
                        ->whereDate('end', '<=', $request->end)
                        ->get(['id', 'title', 'start', 'end', 'etat', 'patient_id']);

            return response()->json($data->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title . ' (' . $event->patient->nom . ' ' . $event->patient->prenom . ')',
                    'start' => $event->start,
                    'end' => $event->end,
                    'etat' => $event->etat,
                    'patient_id' => $event->patient_id,
                ];
            }));
        }
        return view('rdvs.index');
    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = Rdv::create([
                    'patient_id' => $request->patient_id,
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'allDay' => $request->allDay ?? false, // Default to false if not set
                    'etat' => $request->etat ?? 'ouvert', // Default to 'ouvert' if not set
                ]);

                return response()->json($event);

                case 'update':
                    $event = Rdv::find($request->id);
                    if (!$event) {
                        return response()->json(['success' => false, 'message' => 'Event not found.']);
                    }

                    $validatedData = $request->validate([
                        'title' => 'required|string',
                        'start' => 'required|date',
                        'end' => 'required|date',
                    ]);

                    $event->update([
                        'title' => $validatedData['title'],
                        'start' => $validatedData['start'],
                        'end' => $validatedData['end'],
                        'allDay' => $request->allDay ? 1 : 0,
                        'etat' => $request->etat ?? $event->etat,
                    ]);

                    return response()->json(['success' => true, 'event' => $event]);
            case 'delete':
                $event = Rdv::find($request->id);
                if ($event) {
                    $event->delete();
                    return response()->json(['success' => true]);
                }
                return response()->json(['success' => false, 'message' => 'Event not found.']);

            default:
                return response()->json(['success' => false, 'message' => 'Invalid request type.']);
        }
    }
}
