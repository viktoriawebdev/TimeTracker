<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the comments for the blog post.
     */
    public function timeLog(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }

    public static function getWithTimeLogs()
    {
        return self::with('timeLog')->get()->map(
            function ($project) {
                $project['time'] = $project['timeLog']->sum('time_spent');
                unset($project['timeLog']);
                return $project;
            }
        );
    }
}
