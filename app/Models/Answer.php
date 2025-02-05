<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'text',
        'score',
        'question_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
