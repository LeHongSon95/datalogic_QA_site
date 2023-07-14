<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
	use HasFactory;
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title',
		'answer_id',
		'note',
		'view_count',
		'favorite_count',
		'status',
		'created_by',
		'updated_by',
	];

	const ON = 1;

	// get categories for the question
	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class, 'category_questions', 'question_id', 'category_id')->withTimestamps()->orderByPivot('category_id');
	}

	// get tags for the question
	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class, 'tag_questions', 'question_id', 'tag_id')->withTimestamps();
	}

	// get answer for the question
	public function answers(): HasOne
	{
		return $this->hasOne(Answer::class, 'id', 'answer_id');
	}
	// get list QA
	public function question(): BelongsToMany
	{
		return $this->belongsToMany(Category::class, 'category_questions');
	}

	// get questionnaires
	public function questionnaires(): HasMany
	{
		return $this->hasMany(Questionnaire::class, 'question_id');
	}

	// get question recommend
	public function questionRecommend(): HasOne
	{
		return $this->hasOne(QuestionRecommend::class, 'question_id');
	}

	// get question related
	public function questionRelateds(): HasMany
	{
		return $this->hasMany(QuestionRelated::class, 'question_id');
	}

	public function scopeActive($query)
	{
		return $query->where('status', config('constants.status.enable'));
	}
}
