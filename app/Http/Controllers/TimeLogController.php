<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\timeLog;
class TimeLogController extends Controller
{

    public function createLogInstance(Request $request) {
        $result = array('rfid'=>$request->input('rfid'));
        // Means that something was parsed
        if (is_array($result)) {
            $lowRow = timeLog::create($result);
            return response()->json(["success"=>"Logg entry created", 'data' => $lowRow], 201);
        }

        return response()->json(["error"=>"invalid request"], 400);
    }

    // How it should work
    // - It does not reject rediciolus inputs unless they are over 24 hours.
    // - Remove RFID from the row and set done-time
    public function updateLogEntry(Request $request, $id) {
        // In theory there should only be one active row for each student card
        $logEntry = timeLog::where('rfid', '=', $id)->whereNull('stop')->firstOrFail();
        if ($logEntry != null) {
            timeLog::update(['stop', 'rfid'], [$request->input("stop"), 'DONE']);
            try{
                return responce()->json(['success'=>"Updated element with id $id", 'data'=>$logEntry], 200); 
            } catch (Exception $e) {
                return response()->json(['error'=>'update could not be performed'], 500);
            }
        }

        return response()->json(['error'=>"Element not found"], 400);
    }


    // Extract data between two points of time
    public function getLogData(Request $request) {

        try {
            $logEntries = timeLog::where('created_at', '>=', $reqest->input('beginning'))
                  ->where('stop', '<=', $request->input('end'))->get();

            return response()->json(['success'=>'extraction successfull', 'data'=>$logEntries], 500);
        } catch (Exception $e) {
            return response()->json(['error'=>'could not retreive data'], 500);
        }
    }
}

