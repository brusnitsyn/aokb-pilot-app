<?php

namespace App\Http\Controllers;

use App\Models\PatientResultStatus;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function statuses(Request $request)
    {
        $statuses = PatientResultStatus::all();
        return response()->json([
            'statuses' => $statuses
        ]);
    }
}
