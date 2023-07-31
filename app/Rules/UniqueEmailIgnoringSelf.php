<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueEmailIgnoringSelf implements ValidationRule
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $existingEmail = DB::table('users')
            ->where('email', $value)
            ->where('id', '!=', $this->userId)
            ->exists();

        if ($existingEmail) {
            $fail('The email has already been taken.');
        }
    }
}
