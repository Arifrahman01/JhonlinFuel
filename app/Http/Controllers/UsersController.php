<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request): View
    {
        $data = User::get();
        return view('config.users.index');
    }
}
