<?php

namespace App\Rules;

use App\Models\Question;
use Illuminate\Contracts\Validation\Rule;

class UniqueRule implements Rule
{
    protected $question;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($question)
    {
        $this->question = $question;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $questions = Question::select('id', 'title');
        
        if ($this->question) {
            $questions = $questions->where('id', '!=', $this->question);
        }
        $questions = $questions->get();

        $titleDB = $questions->map(function ($item) {
            $item = strip_tags($item['title']);
            $item = trim(preg_replace('/\s\s+/', ' ', $item));
            return strip_tags(htmlentities($item));
        })->toArray();

        foreach ($value as $item) {
            $item = strip_tags($item);
            $item = trim(preg_replace('/\s\s+/', ' ', $item));
            if (in_array($item, $titleDB)) {
                return false;
            } else {
                $titleDB[] = $item;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '質問は存在しています。';
    }
}
