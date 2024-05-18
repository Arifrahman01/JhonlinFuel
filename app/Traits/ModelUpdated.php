<?php

namespace App\Traits;

trait ModelUpdated
{
    public static function bootModelUpdated()
    {
        $user = auth()->user();
        static::updating(function ($model) use ($user) {
            if (!$model->isDirty('updated_id') && !is_null($user)) {
                $model->updated_id = $user->id;
            }
        });
    }
}
