<?php

namespace App\Http\Controllers;

use App\Models\Itineraries;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItinerariesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itineraries = Itineraries::all();

        return response()->json($itineraries, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = auth()->id();

        $request->validate([
            'title' => 'required|string|max:255', 
            'category' => 'required|string|max:255', 
            'duration' => 'required|integer|min:1', 
            'image' => 'required', 
        ]);

        $create = Itineraries::create([
            'user_id' => $id,
            'title' => $request->title,
            'category' => $request->category,
            'duration' => $request->duration,
            'image' => $request->image,
        ]);

        if ($create) {
            return response()->json($create, 201);
        } else {
            return response()->json(
                [
                    'message' => 'There must be an error!'
                ], 500
            );
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255', 
            'category' => 'required|string|max:255', 
            'duration' => 'required|integer|min:1', 
            'image' => 'required', 
        ]);

        $itinerary = Itineraries::findOrFail($id);

        $itinerary->title = $validated['title'];
        $itinerary->category = $validated['category'];
        $itinerary->duration = $validated['duration'];
        $itinerary->image = $validated['image'];

        $itinerary->save();

        return response()->json([
            'message' => 'Itinerary updated successfully'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $itineraries = Itineraries::findOrFail($id);

        $itineraries->delete();

        return response()->json([
            'message' => 'delete'
        ]); 
    }

    public function addToWishlist($itinerary_id)
    {
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'itineraries_id' => $itinerary_id
        ]);

        return response()->json(['message' => 'Itinerary',200]);
    }

    public function getWishList()
    {
        $user = Auth::user();

        $wishlistedItineraries = $user->wishlist()->with('itinerary')->get()->pluck('itinerary');

        return response()->json($wishlistedItineraries, 200);
    }


    public function getDestinationToItinerarie()
    {
        $itineraries = DB::table('itineraries')
            ->leftJoin('destinations', 'itineraries.id', '=', 'destinations.itineraries_id') 
            ->select(
                'itineraries.id as itinerary_id',
                'itineraries.title',
                'itineraries.category',
                'itineraries.duration',
                'itineraries.image',
                'itineraries.user_id',
                'destinations.id as destination_id',
                'destinations.name as destination_name',
                'destinations.logement',
                'destinations.places_to_visit'
            )
            ->get()
            ->groupBy('itinerary_id') 
            ->map(function ($items) {
                $itinerary = $items->first();

                return [
                    'id' => $itinerary->itinerary_id,
                    'title' => $itinerary->title,
                    'category' => $itinerary->category,
                    'duration' => $itinerary->duration,
                    'image' => $itinerary->image,
                    'user_id' => $itinerary->user_id,
                    'destinations' => $items->map(function ($destination) {
                        return [
                            'id' => $destination->destination_id,
                            'name' => $destination->destination_name,
                            'logement' => $destination->logement,
                            'places_to_visit' => json_decode($destination->places_to_visit, true) ?? [] 
                        ];
                    })->filter()
                ];
            })
            ->values();

        return response()->json($itineraries, 200);
    }



    public function search(Request $request)
    {
        $query = Itineraries::query();
    
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
    
        if ($request->has('duration')) {
            $query->where('duration', $request->duration);
        }
    
        $itineraries = $query->get();
    
        return response()->json($itineraries, 200);
    }
    

}
