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

    public function update(Request $request)
    {
        $responses = $request->all();
        $department = Department::with('region')->find($responses['id']);

        return response('')->cookie('activeDepartment', json_encode([
            'id' => $department->id,
            'name' => $department->name,
            'region' => $department->region->shortName
        ]), config('session.lifetime'));
    }

    public function search(Request $request)
    {
        $searchValue = $request->input('search');
        $departments = Department::with('region')->where('name', 'ilike', '%' . $searchValue . '%')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'region' => $item->region->shortName
                ];
        });

        return response()->json($departments);
    }
}
