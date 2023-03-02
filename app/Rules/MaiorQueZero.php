<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaiorQueZero implements Rule
{
    public function passes($attribute, $value)
    {
        return $value > 0;
    }

    public function message()
    {
        return 'O campo :attribute deve ser um número maior que zero.';
    }
}