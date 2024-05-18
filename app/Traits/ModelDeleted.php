<?php

namespace App\Traits;

trait ModelDeleted
{
    public static function bootModelDeleted()
    {
        $user = auth()->user();
        static::deleting(function ($model) use ($user) {
            if (!$model->isDirty('deleted_id') && !is_null($user)) {
                $model->deleted_id = $user->id;
            }
        });
    }
}
