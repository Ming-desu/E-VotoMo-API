<?php

namespace EVotoMo\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    protected $guarded = [];
    protected $hidden = ['password'];
    public $timestamps = false;
}