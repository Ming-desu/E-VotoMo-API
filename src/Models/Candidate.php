<?php

namespace EVotoMo\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model {
    protected $table = "session_candidates";
    protected $guarded = [];
    protected $hidden = ['position_id', 'student_id'];
    public $timestamps = false;

    public function student() {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

    public function position() {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }
}