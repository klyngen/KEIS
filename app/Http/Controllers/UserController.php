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


        try {
            User::create(['name' => $request->input('name'),
                'studentNumber' => $request->input('studentNumber'),
                'email' => $request->input('email')]);
            return response()->json(["success"=>"user created"], 201);
        } catch (Exception $e) {
            return response()->json(["error"=>"could not add user"], 404);
        }
    }

    public function activeRent($id) {
        // RETURN STRUCTURE
        // RENT:ARRAY
        // - INSTANCCE

        $rent = Rent::where('users', $id)->whereNull('stop')
              ->join('instances', 'rents.instances', "=", "instances.id")
              ->join('equipment', 'instances.equipment', "=", "equipment.id")->get();

        return response()->json($rent, 200);
    }

    /**
     *  Also try to find a user by RFID if no user is found by the ID
     */
    public function findUserById(Request $request, $id) {
        $users = User::where('studentNumber', 'like', "$id%")->get();

        if ($users == null) {
            // No user found by ID, try using RFID
            $users = User::where("rfid", "like", $id);

            // If no user is found
            if ($users == null) {
                return response()->json(['error'=>'could not find user'], 412);
            }
        }

        // Return one or many users
        return response()->json($users, 200);
     }
}
