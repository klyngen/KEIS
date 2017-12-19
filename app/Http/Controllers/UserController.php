<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Rent;

class UserController extends Controller
{
    public function index() {
        return response()->json(User::all(), 200);
    }

    public function show($id) {
        return response()->json(User::find($id), 200);
    }

    public function delete(Request $request, $id) {
        $user = User::find($id);
        if ($user != null) {
            $user->delete();
            return response()->json(["success"=>"User with number {$user->studentNumber} was deleted"]);
        }

        return response()->json(['error'=>'User not found'], 404);
    }


    public function store(Request $request) {

        $studentNumber = User::where('studentNumber', $request->input('studentNumber'))->get();

        if ($studentNumber->isNotEmpty()) {
            return response()->json(['error'=>'Student already exists'], 400);
        }


        $user = User::create(['name'=>$request->input('name'),
                              'studentNumber'=>$request->input('studentNumber'),
                              'email'=>$request->input('email')]);

        return response()->json($user->id, 201);
    }

    public function activeRent($id) {
        // RETURN STRUCTURE
        // RENT:ARRAY
        // - INSTANCCE

        $rent = Rent::where('users', $id)->whereNull('stop')
              ->join('instances', 'rents.instances', "=", "instances.id")
              ->join('equipment', 'instances.equipment', "=", "equipment.id")->get();

        return response()->json($rent, 200);return response()->json($rent, 200);
    }

}
