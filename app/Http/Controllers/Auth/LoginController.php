<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Arahkan ke dashboard setelah login
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        // Kosongkan agar tidak error di Laravel 11/12
    }
}