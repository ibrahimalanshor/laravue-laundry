<?php 

namespace App\Services\Auth;

interface AuthServiceInterface
{

    public function login(array $credentials): String;

}

 ?>