<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('region')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'region' => $item->region->shortName
            ];
        })->groupBy('region');

        return response()->json($departments);
    }

    public function userStore(Request $request)
    {
        $responses = $request->all();
        $department = Department::find($responses['id'])->toArray();

//        dd($request->session());
        $request->session()->put('activeDepartment', $department);

    }
}
