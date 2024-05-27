<?php

namespace App\Traits;

trait ModelDeleted
{
    public static function bootModelDeleted()
    {
        // dd('delete 1');
        $user = auth()->user();
        static::deleting(function ($model) use ($user) {
            if (!$model->isDirty('deleted_id') && !is_null($user)) {
                $model->deleted_id = $user->id;
                $model->save();
            }
        });
    }
}
