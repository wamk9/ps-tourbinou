<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Exception;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        try
        {
            $tours = Tour::selectRaw("
                tours.id,
                tours.name,
                tours.description,
                CONCAT(destinations.name, ' - ', states.abbreviation) AS destination,
                hours.name AS hour
            ")
            ->leftJoin('destinations', 'destinations.id', '=', 'tours.destination_id')
            ->leftJoin('hours', 'hours.id', '=', 'tours.hour_id')
            ->leftJoin('states', 'states.id', '=', 'destinations.state_id')
            ->get();


            return response()->json([
                'message' => $tours
            ], 200);
        }
        catch (Exception $ex)
        {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }
}
