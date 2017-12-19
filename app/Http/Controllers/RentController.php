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

        return response()->json($rent->id, 201);
    }

    public function deliver(Request $request, $id) {
        $rent = Rent::where('id', $id)->first();

        $now = Carbon::now();

        $rent->stop = $now->toDateTimeString();

        $rent->save();
        return response()->json(["success"=>"item delivered"], 200);
    }



    public function isAlreadyRented($id) {
        $rented = Rent::where(['instances'=> $id])->whereNull('stop')->get();
        return $rented->isNotEmpty();
    }

    public function statistics(Request $request) {
        return response()->json($this->notRented(), 200);
    }

    public function getRented() {
        $instances = Instance::select(['instances.*', 'rents.stop', 'rents.id AS rid', 'equipment.model', 'equipment.types', 'equipment.brands'])
                   ->join('equipment', 'equipment.id', '=', 'instances.equipment')
                   ->join('rents', function ($join) {
                       $join->on('rents.instances', '=', 'instances.id')
                           ->whereNull('stop');
                   })->get();
        return $instances;
    }

    public function notRented() {
        $rented =  $this->getRented()->toArray();
        $all = Instance::join('equipment', 'equipment.id', '=', 'instances.equipment')
             ->get()
             ->toArray();

        //dd($all);

        for($ar = 0; $ar < count($all); $ar++) {
            for ($rr = 0; $rr < count($rented); $rr++) {
                 if ($all[$ar]['id'] == $rented[$rr]['id']) {
                     //dd("something unsetted");
                    unset($all[$ar]);
                    break;
                }
            }
        }

        return Array(["rented"=>$rented, "available"=>$all]);
    }
}
