<?php

namespace EVotoMo\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {
    protected $guarded = [];
    protected $hidden = ['added_by'];
    public $timestamps = false;

    public function code() {
        return $this->hasOne(Code::class, 'session_id', 'id');
    }

    public function user() {
        return $this->hasOne(Account::class, 'id', 'added_by');
    }

    public function getVoteStartAttribute($value) {
        return date('M d, Y h:iA', strtotime($value));
    }

    public function getVoteEndAttribute($value) {
        return date('M d, Y h:iA', strtotime($value));
    }
}