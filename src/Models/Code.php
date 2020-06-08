<?php

namespace EVotoMo\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model {
    protected $table = "session_codes";
    protected $guarded = [];
    public $timestamps = false;
}