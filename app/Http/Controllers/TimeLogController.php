<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\timeLog;
use Carbon\Carbon;

class TimeLogController extends Controller
{

    public function createLogInstance(Request $request) {
        $result = array('rfid'=>$request->input('rfid'));
        // Means that something was parsed
        if (is_array($result)) {

            $data = timeLog::whereNull('stop')->where('rfid', $request->input('rfid'))->first();

            if ($data != null) {
                return response()->json(['success'=>'false', 'data'=>$data], 200);
            }

            $lowRow = timeLog::create($result);
            return response()->json(["success"=>"true", 'data' => $lowRow], 201);
        }

        return response()->json(["error"=>"invalid request"], 400);
    }

    // How it should work
    // - It does not reject rediciolus inputs unless they are over 24 hours.
    // - Remove RFID from the row and set done-time
    public function updateLogEntry(Request $request) {
        // In theory there should only be one active row for each student card
        $id = $request->input('rfid');

        // Verify input
        if ($id == null) {
            return response()->json(["error"=>"missing argument", "data"=>"[]"]);
        }

        $logEntry = timeLog::where('rfid', '=', $id)->whereNull('stop')->firstOrFail();


        // If the checkout is not specified and seems stupid
        // Do not store the checkout. Most likely the user being stupid
        if (!$request->has('stop')) {
            $endTime = $this->createEndDate($request);
            $carbonEnd = Carbon::parse($endTime);
            $carbonStart = Carbon::parse($logEntry->created_at);


            $hours = $carbonStart->diffInHours($carbonEnd);

            if ($hours >= 24) {
                return response()->json(["error"=>"the interval is suspiciously large", "data"=>["interval"=>$hours, "solution"=>"add the endtime in the post request if you are a workoholic"]], 409);
            }
        }

        if ($logEntry != null) {
            $logEntry->stop = $this->createEndDate($request);
            $logEntry->save();

            try{
                return response()->json(['success'=>"Updated element with id $id", 'data'=>$logEntry], 200);
            } catch (Exception $e) {
                return response()->json(['error'=>'update could not be performed'], 500);
            }
        }

        return response()->json(['error'=>"Element not found"], 400);
    }


    // Should allow end date to be set in the request or just use the current time
    private function createEndDate(Request $request) {
        $stopTime = $request->input('stop');

        // If set in the post-request return the date
        if ($stopTime != null) {
            return $stopTime;
        }

        return Carbon::now()->toDateTimeString();
    }


    // Extract data between two points of time
    public function getLogData(Request $request) {

        try {
            $logEntries = timeLog::select(['created_at', 'stop'])->where('created_at', '>=', $request->input('beginning'))
                        ->where('stop', '<=', $request->input('end'))->whereNotNull('stop')->get();

            return response()->json(['success'=>'extraction successfull', 'data'=>$logEntries], 500);
        } catch (Exception $e) {
            return response()->json(['error'=>'could not retreive data'], 500);
        }
    }

    public function getLogEntry(Request $request, $id) {
        if ($id == null) {
            return response()->json(["error"=>"missing parameter"], 200);
        }
        try {
            $log = timeLog::where('rfid', '=', $id)->first();
            if ($log == null) {
                return response()->json(["success"=>"false"], 200);
            }

            \Log::warning("$log");

            return response()->json(["success"=>"true", "data"=>$log], 200);
        } catch (Exception $e) {
            return response()->json(["error"=>"server error", "data"=>[]], 500);
        }
    }
}

