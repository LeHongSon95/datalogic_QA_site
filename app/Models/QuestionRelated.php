<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionRelated extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id',
        'question_related_id',
    ];

    protected $table = 'question_relateds';

    public function question()
	{
		return $this->belongsTo(Question::class, 'question_related_id', 'id');
	}
}
