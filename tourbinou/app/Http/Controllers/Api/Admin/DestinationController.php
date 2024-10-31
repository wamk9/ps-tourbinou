<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\State;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
{
    public function stateIndex() {
        try
        {
            $states = State::selectRaw("states.id AS value, CONCAT(states.name, ' (', states.abbreviation, ')') AS title")->get();

            return response()->json([
                'message' => $states
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
            $destinations = Destination::selectRaw("
                    destinations.id AS id,
                    destinations.name AS name,
                    CONCAT(states.name, ' (', states.abbreviation, ')') AS state,
                    IF(COUNT(tours.id) > 0, CONCAT(COUNT(tours.id), IF(COUNT(tours.id) > 1, ' Passeios', ' Passeio')), 'Nenhum passeio') AS tour_qtd
                ")
                ->leftJoin('states', 'states.id', '=', 'destinations.state_id')
                ->leftJoin('tours', 'tours.destination_id', '=', 'destinations.id') // Adjusted join condition
                ->groupBy(['destinations.id', 'destinations.name', 'states.name', 'states.abbreviation'])
                ->get();


            return response()->json([
                'message' => $destinations
            ], 200);
        }
        catch (Exception $ex)
        {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        try
        {
            $destinations = Destination::selectRaw("
                destinations.id AS value,
                CONCAT(destinations.name, ' - ', states.abbreviation) AS title
            ")
            ->leftJoin('states', 'states.id', '=', 'destinations.state_id')
            ->get();


            return response()->json([
                'message' => $destinations
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
                'id' => 'required|exists:destinations,id',
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $destination = Destination::where([
                ['id', $dataGetted['id']],
            ])->first();

            $destination->delete();

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
                'id' => 'required|exists:destinations,id',
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $destination = Destination::where([
                ['id', $dataGetted['id']],
            ])->first();

            $returnMessage = response()->json(['message' => $destination], 200);
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
                'state_id',
            ]);

            $validator = Validator::make($dataGetted, [
                'name' => 'required|string',
                'state_id' => 'required|exists:states,id'
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $destination = Destination::create($dataGetted);
            $destination->save();

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

    public function update(Request $request)
    {
        $returnMessage = null;

        try
        {

            $dataGetted = request()->only([
                'id',
                'name',
                'state_id'
            ]);

            $validator = Validator::make($dataGetted, [
                'id' => 'required|exists:destinations,id',
                'name' => 'required|string',
                'state_id' => 'required|exists:states,id',
            ]);

            if ($validator->fails())
            {
                throw new ValidationException($validator->errors());
            }

            $destination = Destination::where([
                ['id', $dataGetted['id']],
            ]);
            $destination->update($dataGetted);

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
