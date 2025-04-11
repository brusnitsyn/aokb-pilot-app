<?php

namespace App\Models;

use App\Observers\PatientResultObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(PatientResultObserver::class)]
class PatientResult extends Model
{
    protected $fillable = [
        'patient_id',
        'sender_department_id',
        'from_department_id',
        'coords',
        'comment',
        'to_department_id',
        'patient_score',
        'department_score',
        'total_score',
        'patient_responses',
        'department_responses',
        'scenario_id',
        'scenario_score',
        'status_id',
        'user_id',
        'last_status_at',
        'status_changed_at'
    ];

    public function sender_department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'sender_department_id');
    }

    public function from_department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    public function to_department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function scenario(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Scenario::class);
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PatientResultStatus::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'patient_responses' => AsArrayObject::class,
            'department_responses' => AsArrayObject::class,
            'coords' => AsArrayObject::class,
        ];
    }
}
