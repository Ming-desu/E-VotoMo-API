<?php

namespace EVotoMo\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {
    protected $table = "system_accounts";
    protected $guarded = [];
    protected $hidden = array('password');
    public $timestamps = false;
}