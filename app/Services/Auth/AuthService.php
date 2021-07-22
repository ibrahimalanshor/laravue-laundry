<?php 

namespace App\Services\Auth;

use App\Exceptions\WrongPasswordException;

use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{

    public function login(array $credentials): String
    {
        if (!Auth::once($credentials)) {
            throw new WrongPasswordException('Password salah');
        }

        $user = Auth::user();
        $token = $user->createToken('halobang', [$user->role])->plainTextToken;

        return $token;
    }

}

 ?>