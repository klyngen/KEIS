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

    public function store(Request $response) {
        // Verify the user
        // Verify the the instance
        $user = User::where('studentNumber', $response->input('studentNumber'))->first();
        $instance = Instance::where('id', $response->input('instance'))->first();

        if ($instance == null || $user == null) {
            dd($user, $instance);
            return response()->json(Array("error"=>"incorrect data"), 404);
        }

        if($this->isAlreadyRented($instance->id)) {
            return response()->json(["error"=>"This equipment is already rented out"], 400);
        }

        $rent = Rent::create(['users'=>$user->id,
                      'instances'=>$instance->id,]);

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
        $instance = Instance::where('id', '=', $id)->fist();
        if ($instance == null) {
            return true;
        }
        return $instance->rented;
    }

    public function statistics(Request $request) {
        $rented = Instance::where('rented', '=', 1)->count();
        $available = Instance::where('rented', '=', 0)->count();

        $week = Carbon::now()->addWeeks(-1)->toDateTimeString();

        $long = Instance::where('rented', '=', 1)
              ->join('rent', 'rent.instances', '=', 'instances.id')->having('created_at', '<', $week)->count();
        return response()->json(['rented'=>$rented, 'available'=>$available, 'long'=>$long]);
    }

}
