<?php

namespace App\models;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public function Permissions ()
    {
        // 多对多的关系（一个用户有多个角色）
        return $this->belongsToMany(Permission::class);
    }
}
