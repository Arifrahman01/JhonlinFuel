<?php

namespace App\Traits;

trait ModelCreated
{
    public static function bootModelCreated()
    {
        $user = auth()->user();
        static::creating(function ($model) use ($user) {
            if (!$model->isDirty('created_id') && !is_null($user)) {
                $model->created_id = $user->id;
            }
            if (!$model->isDirty('updated_id') && !is_null($user)) {
                $model->updated_id = $user->id;
            }
        });
    }
}
