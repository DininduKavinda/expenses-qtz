<?php

namespace App\Observers;

use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        ActivityLogger::log('crud', 'created', $model->getTable(), $model->id, null, $model->toArray());
    }

    public function updated(Model $model): void
    {
        ActivityLogger::log('crud', 'updated', $model->getTable(), $model->id, $model->getOriginal(), $model->getAttributes());
    }

    public function deleted(Model $model): void
    {
        ActivityLogger::log('crud', 'deleted', $model->getTable(), $model->id, $model->toArray());
    }
}
