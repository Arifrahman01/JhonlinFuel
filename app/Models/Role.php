<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    protected $table = 'roles';

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')->using(UserRole::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id', 'id', 'id');
    }

    public function hasDataById(): bool
    {
        return false;
    }
}
