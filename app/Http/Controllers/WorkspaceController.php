<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentAnswer;
use App\Models\DepartmentQuestion;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkspaceController extends Controller
{
    public function show(Request $request)
    {
        $diagnosis = Diagnosis::all();
        $departments = Department::all();

        // Вопросы для медицинской организации
        $organizationQuestions = DepartmentQuestion::with('answers.departments')->get();

        // Cookie
        $activeDepartment = json_decode(\request()->cookie('activeDepartment'));
        $organizationResponses = json_decode(\request()->cookie('organizationResponses'));

        return Inertia::render('Workspace', [
            'diagnosis' => $diagnosis,
            'departments' => $departments,
            'activeDepartment' => $activeDepartment,
            'organizationQuestions' => $organizationQuestions,
            'organizationResponses' => $organizationResponses
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
//            'medical_organization_id' => 'required|exists:departments,id',
            'organization_responses' => 'required|array',
        ]);

        $organizationResponses = $request->input('organization_responses', []);
//        $medicalOrganizationId = $request->input('medical_organization_id');

        return response('')
            ->cookie('organizationResponses',
                json_encode($organizationResponses),
                config('session.lifetime')
            );
    }
}
