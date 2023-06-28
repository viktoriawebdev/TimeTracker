<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeLogRequest;
use App\Models\Project;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Exports\TimeLogExport;
use Maatwebsite\Excel\Facades\Excel;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $time_logs = TimeLog::mine()->get();
        $projects = Project::all();
        $current_tracker = TimeLog::mine()->running()->first();
        $dailyStats = TimeLog::mine()->byDate()->get();
        $monthlyStats = TimeLog::mine()->byMonth()->get();
        return Inertia::render('Dashboard',
            compact(['time_logs', 'projects', 'current_tracker', 'dailyStats', 'monthlyStats']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::findOrFail($request->input('project'));
        $logEntry = new TimeLog(['start' => Carbon::now()]);
        $logEntry->project()->associate($project);
        $logEntry->user()->associate(Auth::user());
        $logEntry->save();
        return [
            'success' => true,
            'timer' => $logEntry
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimeLogRequest $request, TimeLog $time_log)
    {
        $validated = $request->validated();

        $time_log->fill($validated);
        $time_log->project()->associate($request->get('project'));
        $time_log->save();

        return [
            'success' => true
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function stopTimer(Request $request, TimeLog $time_log)
    {
        $time_log->end = Carbon::now();
        $time_log->save();

        return [
            'success' => true
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeLog $time_log)
    {
        $time_log->delete();
        return [
            'success' => true
        ];
    }

    /**
     * Export all logs into excel
     */
    public function export()
    {
        return Excel::download(new TimeLogExport, 'time_logs.xlsx');
    }
}
