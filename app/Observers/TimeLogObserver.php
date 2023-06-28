<?php

namespace App\Observers;

use App\Models\TimeLog;
use Carbon\Carbon;

class TimeLogObserver
{
    /**
     * Handle the TimeLog before a record is saved (either created or updated)
     */
    public function saving(TimeLog $timeLog): void
    {
        if ($timeLog->end !== null) {
            $timeLog->time_spent = Carbon::parse($timeLog->start)->diffInSeconds(Carbon::parse($timeLog->end));
        }
    }
}
