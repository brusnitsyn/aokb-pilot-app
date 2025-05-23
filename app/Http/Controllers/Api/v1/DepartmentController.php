<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $isReceive = $request->input('is_receive');
        $departments = Department::with('region')
            ->where('is_receive', false)->get()->map(function ($item) {
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
        $department = Department::with('region')->find($responses['id'] ?? $responses['sender_department_id']);

        Cookie::queue(Cookie::make('coordsComment', $responses['comment'] ?? null, config('session.lifetime')));
        if(isset($responses['coords'])) Cookie::queue(Cookie::make('lastCoords', json_encode($responses['coords']) ?? null, config('session.lifetime')));
        Cookie::queue(Cookie::make('myDepartment', $department->id, config('session.lifetime')));
        Cookie::queue(Cookie::make('senderDepartment', $responses['sender_department_id'] ?? null, config('session.lifetime')));
//
//        return response('')->cookie('myDepartment', $department->id, config('session.lifetime'));
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
