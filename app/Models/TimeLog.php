<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = ['start', 'end', 'time_spent'];
    protected $with = ['project'];

    /**
     * Get the project that owns the timeLog.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that owns the timeLog.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include records from authorised user.
     */
    public function scopeMine(Builder $query): void
    {
        $query->where('user_id', Auth::id());
    }


    /**
     * Scope a query to only include running trackers.
     */
    public function scopeRunning(Builder $query): void
    {
        $query->whereNull('end');
    }

    /**
     * Scope a query to get total time per day
     */
    public function scopeByDate(Builder $query): void
    {
        $query->select([
            \DB::raw("DATE_FORMAT(start, '%Y-%m-%d') as date"),
            \DB::raw('COUNT(id) as counter'),
            \DB::raw('SUM(time_spent) as amount')
        ])->groupBy('date');
    }

    /**
     * Scope a query to get total time per month
     */
    public function scopeByMonth(Builder $query): void
    {
        $query->select([
            \DB::raw("DATE_FORMAT(start, '%Y-%m') as date"),
            \DB::raw('COUNT(id) as counter'),
            \DB::raw('SUM(time_spent) as amount')
        ])->groupBy('date');
    }
}
