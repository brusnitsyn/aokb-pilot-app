<?php

namespace App\Http\Controllers;

use App\Models\PatientResultStatus;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function statuses(Request $request)
    {
        $notInclude = [1];
        if ($request->input('current_status_id')) {
            $notInclude[] = [$request->input('current_status_id')];
        }
        $statuses = PatientResultStatus::whereNotIn('id', $notInclude)->get();
        return response()->json([
            'statuses' => $statuses
        ]);
    }
}
