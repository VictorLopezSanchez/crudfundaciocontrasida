<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name', 'department_id', 'phone'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
