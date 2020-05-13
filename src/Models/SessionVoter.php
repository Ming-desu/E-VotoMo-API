<?php

namespace EVotoMo\Models;

use Illuminate\Database\Eloquent\Model;

class SessionVoter extends Model {
    protected $table = "session_voters";
    protected $guarded = [];
    public $timestamps = false;
}