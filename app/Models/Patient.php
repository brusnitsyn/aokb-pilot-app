<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Patient extends Model
{
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $yearNow = Carbon::now()->year;
            $monthNow = Carbon::now()->month;

            $count = PatientResult::whereYear('created_at', $yearNow)
                ->whereMonth('created_at', $monthNow)
                ->get()
                ->count();

            $now = Carbon::now()->format('Ym');
            $count = $count + 1;

            $model->number = "$now/$count";
            $model->full_name = "$model->last_name $model->first_name $model->middle_name";
        });
    }

    protected $fillable = [
        'number',
        'first_name',
        'last_name',
        'middle_name',
        'full_name',
        'total_score',
        'diagnosis_id',
        'date_birth',
        'snils',
    ];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }
}
