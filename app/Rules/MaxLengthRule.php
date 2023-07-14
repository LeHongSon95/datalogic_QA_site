<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxLengthRule implements Rule
{
    protected $maxlength;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($maxlength)
    {
        $this->maxlength = $maxlength;
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
        if ($attribute == 'answer' || $attribute == 'note') {
            if (mb_strlen(strip_tags($value)) > $this->maxlength ) {
                return false;
            }
        } else {
            foreach ($value as $item) {
                $item = strip_tags($item);
                $item = trim(preg_replace('/\s\s+/', ' ', $item));
                if (mb_strlen($item) > $this->maxlength ) {
                    return false;
                }
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
        return ':attributeは' .$this->maxlength. '文字以下入力してください。';
    }
}
