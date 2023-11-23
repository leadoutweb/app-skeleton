<?php

namespace App\Http\Controllers\V1\Authentication;

use App\Http\Controllers\V1\Controller;
use App\Http\Resources\Authentication\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

class ActivityController extends Controller
{
    /**
     * Show a list of activity.
     */
    public function index(): AnonymousResourceCollection
    {
        $activity = QueryBuilder::for(Activity::class)
            ->allowedFilters(['id', 'subject_type', 'subject_id', 'causer_type', 'causer_id'])
            ->apiPaginate();

        return UserResource::collection($activity);
    }
}
