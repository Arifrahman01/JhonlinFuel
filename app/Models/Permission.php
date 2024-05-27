<?php

namespace App\Models;

class Permission extends BaseModel
{
    protected $table = 'permissions';

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
