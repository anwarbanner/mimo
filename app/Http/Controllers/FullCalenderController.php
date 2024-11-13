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
                ->get();

            return response()->json($data->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start,
                    'end' => $event->end,
                    'etat' => $event->etat,
                    'patient_id' => $event->patient_id,
                    'patient_nom' => $event->patient->nom,
                    'patient_prenom' => $event->patient->prenom,
                    'telephone'=> $event->patient->telephone,
                ];
            }));
        }
        return view('rdvs.index');
    }

    public function ajax(Request $request)
    {
        // Validation rules
        $rules = [
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'patient_id' => 'required|exists:patients,id',
        ];

        // Additional message for custom validation
        $messages = [
            'end.after_or_equal' => 'The end time must be after the start time.',
            'same_day' => 'The event must start and end on the same day.',
        ];

        switch ($request->type) {
            case 'add':
                // Ensure start time is not in the past
                if (strtotime($request->start) < time()) {
                    return response()->json(['success' => false, 'message' => 'The start time cannot be in the past.']);
                }

                // Validate event time constraints
                $request->validate($rules + ['end' => function ($attribute, $value, $fail) use ($request) {
                    if (date('Y-m-d', strtotime($request->start)) !== date('Y-m-d', strtotime($value))) {
                        $fail('The event must start and end on the same day.');
                    }
                }], $messages);

                // Create the event
                $event = Rdv::create([
                    'patient_id' => $request->patient_id,
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'allDay' => $request->allDay ?? false,
                    'etat' => $request->etat ?? 'ouvert',
                ]);

                return response()->json($event);

            case 'update':
                // Ensure start time is not in the past
                if (strtotime($request->start) < time()) {
                    return response()->json(['success' => false, 'message' => 'The start time cannot be in the past.']);
                }

                // Find and update the event
                $event = Rdv::find($request->id);
                if (!$event) {
                    return response()->json(['success' => false, 'message' => 'Event not found.']);
                }



                // Update the event
                $event->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
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
                return response()->json(['success' => false, 'message' => 'Invalid action.']);
        }
    }
}
