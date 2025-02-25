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

        $myDepartment = $myDepartmentId ? Department::whereId((integer)$myDepartmentId)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'region' => $item->region->shortName
            ];
        })->first() : null;

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
