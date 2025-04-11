<?php

namespace App\Observers;

use App\Models\PatientResult;
use Illuminate\Support\Carbon;

class PatientResultObserver
{
    public function creating(PatientResult $patientResult): void
    {
        $patientResult->status_changed_at = Carbon::now(config('app.timezone'))->getTimestampMs();
        $patientResult->last_status_at = Carbon::now(config('app.timezone'))->getTimestampMs();
    }

    /**
     * Handle the PatientResult "created" event.
     */
    public function created(PatientResult $patientResult): void
    {
        //
    }

    public function updating(PatientResult $patientResult): void
    {
        $originalStatus = $patientResult->getOriginal('status_id');
        if ($originalStatus !== $patientResult->status_id) {
            $patientResult->last_status_at = $patientResult->status_changed_at;
            $patientResult->status_changed_at = Carbon::now(config('app.timezone'))->getTimestampMs();
        }
    }

    /**
     * Handle the PatientResult "updated" event.
     */
    public function updated(PatientResult $patientResult): void
    {
        //
    }

    /**
     * Handle the PatientResult "deleted" event.
     */
    public function deleted(PatientResult $patientResult): void
    {
        //
    }

    /**
     * Handle the PatientResult "restored" event.
     */
    public function restored(PatientResult $patientResult): void
    {
        //
    }

    /**
     * Handle the PatientResult "force deleted" event.
     */
    public function forceDeleted(PatientResult $patientResult): void
    {
        //
    }
}
