<?php

namespace App\Http\Middleware;

use App\Models\Department;
use App\Models\UserDepartment;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $myDepartmentId = json_decode(\request()->cookie('myDepartment'));

        $myDepartment = auth()->hasUser() && auth()->user()->myDepartment() !== null ? [
            'id' => auth()->user()->myDepartment()->id,
            'name' => !is_null(auth()->user()->myDepartment()->shortname) ? auth()->user()->myDepartment()->shortname : auth()->user()->myDepartment()->name,
            'region' => auth()->user()->myDepartment()->region()->exists() ? auth()->user()->myDepartment()->region->shortName : null,
            'coords' => auth()->user()->myDepartment()->coords,
//            'is_receive' => auth()->user()->myDepartment()->is_receive,
        ] : null;

        return array_merge([
            ...parent::share($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ], [
            'auth' => [
                'user' => $request->user() ? [
                    ...$request->user()->load(['role.scopes'])->toArray(),
                    'department' => $myDepartment
                ] : null
            ]
        ]);
    }
}
