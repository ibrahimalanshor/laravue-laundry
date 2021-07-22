<?php

namespace App\Exceptions;

use Exception;

class WrongPasswordException extends Exception
{

    public function render()
    {
        return response()->json(['errors' => [
            'password' => [$this->message]
        ]], 401);
    }

}
