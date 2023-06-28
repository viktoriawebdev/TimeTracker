<?php
if (!function_exists('sum_of_time_per_project')) {
    function sum_of_time_per_project($collection)
    {
        return $collection->map(
            function ($project) {
                $project['time'] = $project['timeLog']->sum('time_spent');
                unset($project['timeLog']);
                return $project;
            }
        );
}
}
