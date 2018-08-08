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
        return response()->json(['success' => 'found', 'data'=>Rent::whereNull('stop')->get()], 200);
    }

    public function show($id) {
        return response()->json(Rent::whereNull('stop')->find($id), 200);
    }

    /**
     * @param Request $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $response) {
        if (!$response->has('studentNumber') && !$response->has('instance')) {
            return response()->json(['error'=>'missing parameters'], 400);
        }

        $user = User::where('studentNumber', '=', $response->input('studentNumber'))->first();
        $instance = Instance::where('id', '=', $response->input('instance'))->first();
        error_log($response);
        error_log($instance);
        error_log($user);
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

    public function deliver(Request $request) {
        //try {
        //$rent = Rent::join('instances', 'rents.instances', '=', 'instances.id')
        //->where('RFID', $request->input("RFID"))
        //->whereNull("stop")->orderBy('rents.id', 'desc')->first();
        //$instance = Instance::where('id', '=', $rent->instances)->first();
        //$instance->rented = 0;

        $instance = Instance::where('RFID', '=', $request->input('RFID'))->first();
        $instance->rented = 0;

        if ($request->has('condition')) {
            $instance->condition = $request->input('condition');
        }
        $instance->save();

        $rent = Rent::whereNull('stop')->where('instances', $instance->id)->first();
        $rent->stop = Carbon::now()->toDateTimeString();
        $rent->save();


            //} catch (\Exception $e) {
            //return response()->json(["error"=>"something has gone wrong during delivery"], 500);
            //}

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
