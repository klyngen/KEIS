<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rent;
use App\User;
use App\Instance;
use Carbon\Carbon;

class RentController extends Controller
{
    public function index() {
        return response()->json(Rent::all(), 200);
    }

    public function show($id) {
        return response()->json(Rent::find($id), 200);
    }

    /**
     * @param Request $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $response) {
        $user = User::where('studentNumber', '=', $response->input('studentNumber'))->first();
        $instance = Instance::where('id', '=', $response->input('instance'))->first();

        if ($instance == null || $user == null) {
            return response()->json(Array("error"=>"incorrect data"), 404);
        }


        if($this->isAlreadyRented($instance->id)) {
            return response()->json(["error"=>"This equipment is already rented out"], 400);
        }

        try {
            $rent = Rent::create(['users'=>$user->id,
                'instances'=>$instance->id]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error'=>"something is wrong with creating the rent"]);
        }

        $instance->rented = 1;
        $instance->save();

        return response()->json($rent->id, 201);
    }

    public function deliver(Request $request, $id) {
        $rent = Rent::where('id', $id)->first();
        $instance = Instance::where('id', '=', 'instances')->first();
        $instance->rented = 0;

        $instance->save();

        $now = Carbon::now();

        $rent->stop = $now->toDateTimeString();

        $rent->save();
        return response()->json(["success"=>"item delivered"], 200);
    }



    public function isAlreadyRented($id) {
        $instance = Instance::where('id', '=', $id)->first();
        if ($instance->rented == 1) {
            return true;
        }
        return false;
    }

    public function statistics(Request $request) {
        $rented = Instance::where('rented', '=', 1)->count();
        $available = Instance::where('rented', '=', 0)->count();

        $week = Carbon::now()->addWeeks(-1)->toDateTimeString();
        $long = Instance::where('rented', '=', 1)
              ->join('rents', 'rents.instances', '=', 'instances.id')->where('rents.created_at', '<', $week)->count();
        $rented = $rented - $long;
        return response()->json(['rented'=>$rented, 'available'=>$available, 'long'=>$long]);
    }

}
