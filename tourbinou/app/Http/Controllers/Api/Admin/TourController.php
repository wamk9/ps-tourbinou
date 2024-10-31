<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hour;
use App\Models\Tour;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TourController extends Controller
{
    public function hourIndex() {
        try
        {
            $hours = Hour::selectRaw("hours.id AS value, hours.name AS title")->get();

            return response()->json([
                'message' => $hours
            ], 200);
        }
        catch (Exception $ex)
        {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try
        {
            $tours = Tour::selectRaw("
                    tours.id AS id,
                    tours.name AS name,
                    hours.name AS hour,
                    CONCAT(destinations.name, ' - ', states.abbreviation) AS destination
                ")
                ->leftJoin('destinations', 'tours.destination_id', '=', 'destinations.id')
                ->leftJoin('states', 'states.id', '=', 'destinations.state_id')
                ->leftJoin('hours', 'tours.hour_id', '=', 'hours.id')
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

    public function delete()
    {
        $returnMessage = null;

        try
        {
            $dataGetted = [
                'id' => request()->route()->parameter('id'),
            ];

            $validator = Validator::make($dataGetted, [
                'id' => 'required|exists:tours,id',
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $tour = Tour::where([
                ['id', $dataGetted['id']],
            ])->first();

            $tour->delete();

            $returnMessage = response()->json(['message' => 'success'], 200);
        }
        catch (ValidationException $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 422);
        }
        catch (Exception $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 500);
        }

        return $returnMessage;
    }

    public function show()
    {
        $returnMessage = null;

        try
        {
            $dataGetted = [
                'id' => request()->route()->parameter('id'),
            ];

            $validator = Validator::make($dataGetted, [
                'id' => 'required|exists:tours,id',
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $tour = Tour::where([
                ['id', $dataGetted['id']],
            ])->first();

            $returnMessage = response()->json(['message' => $tour], 200);
        }
        catch (ValidationException $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 422);
        }
        catch (Exception $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 500);
        }

        return $returnMessage;
    }

    public function store()
    {
        $returnMessage = null;

        try
        {
            $dataGetted = request()->only([
                'name',
                'hour_id',
                'destination_id',
                'description'
            ]);

            $validator = Validator::make($dataGetted, [
                'name' => 'required|string',
                'hour_id' => 'required|exists:hours,id',
                'destination_id' => 'required|exists:destinations,id',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $tour = Tour::create($dataGetted);
            $tour->save();

            $returnMessage = response()->json(['message' => 'success'], 200);
        }
        catch (ValidationException $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 422);
        }
        catch (\Throwable $th)
        {
            $returnMessage = response()->json(['message' => $th->getMessage()], 500);
        }

        return $returnMessage;
    }

    public function update()
    {
        $returnMessage = null;

        try
        {

            $dataGetted = request()->only([
                'id',
                'name',
                'hour_id',
                'destination_id',
                'description'
            ]);

            $validator = Validator::make($dataGetted, [
                'id' => 'required|exists:tours,id',
                'name' => 'required|string',
                'hour_id' => 'required|exists:hours,id',
                'destination_id' => 'required|exists:destinations,id',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $tour = Tour::where([
                ['id', $dataGetted['id']],
            ]);
            $tour->update($dataGetted);

            $returnMessage = response()->json(['message' => 'success'], 200);
        }
        catch (ValidationException $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 422);
        }
        catch (Exception $ex)
        {
            $returnMessage = response()->json(['message' => $ex->getMessage()], 500);
        }

        return $returnMessage;
    }
}
