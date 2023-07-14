<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class QuestionItemNotNull implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // for import
        if (gettype($value) !== 'array') {
            $value = explode(",", $value);
        }
        //
        foreach ($value as $item) {
            if (!$item) {
                return false;
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
        return ':attribute項目は必須です。';
    }
}
