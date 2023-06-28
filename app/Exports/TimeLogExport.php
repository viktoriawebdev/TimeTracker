<?php

namespace App\Exports;

use App\Models\TimeLog;
use Maatwebsite\Excel\Concerns\FromCollection;

class TimeLogExport implements FromCollection
{
    public function collection()
    {
        return TimeLog::all();
    }
}
