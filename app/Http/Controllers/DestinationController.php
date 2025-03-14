<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Itineraries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destination = Destination::all();
        return response()->json($destination, 200); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $itineraries_id)
    {
        $user = Auth::user();
        $itinerary = Itineraries::where('id', $itineraries_id)->where('user_id', $user->id)->first();

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found or not owned by user.'], 404);
        }
    
        if (count($request->destinations) < 2) {
            return response()->json(['message' => 'You must add at least two destinations to this itinerary.'], 400);
        }
    
        $request->validate([
            'destinations' => 'required|array',
            'destinations.*.name' => 'required|string|max:255',
            'destinations.*.logement' => 'required|string|max:255',
            'destinations.*.places_to_visit' => 'nullable|array',
        ]);
    
        $destinations = [];
        foreach ($request->destinations as $destinationData) {
            $destinations[] = Destination::create([
                'itineraries_id' => $itineraries_id,
                'name' => $destinationData['name'],
                'logement' => $destinationData['logement'],
                'places_to_visit' => json_encode($destinationData['places_to_visit']),
            ]);
        }
    
        return response()->json($destinations, 201);
    }
    



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logement' => 'required|string|max:255',
            'places_to_visit' => 'nullable|array',
        ]);

        $destination = Destination::findOrFail($id);

        $destination->name = $validated['name'];
        $destination->logement = $validated['logement'];
        $destination->places_to_visit = $validated['places_to_visit'];

        $destination->save();

        return response()->json([
            'message' => 'destination updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $destination = Destination::findOrFail($id);

        $destination->delete();

        return response()->json([
            'message' => 'delete'
        ]);
    }
}
