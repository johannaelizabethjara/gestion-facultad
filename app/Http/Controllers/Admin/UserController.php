<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;



class UserController extends Controller
{
    public function index()
    {
        // Trae todos los usuarios de la tabla users
        $users = User::all();

        // Pasa los usuarios a la vista
        return view('users.index', compact('users'));
    }
}

