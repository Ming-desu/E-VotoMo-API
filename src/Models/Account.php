<?php

namespace EVotoMo\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {
    protected $guarded = [];
    protected $hidden = array('password');
    public $timestamps = false;
}