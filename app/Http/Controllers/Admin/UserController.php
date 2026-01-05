<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function index()
    {
        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }


    public function ban(User $user)
    {
        $user->update(['banned_at' => now()]);

        return back()->with('success', 'Пользователь успешно заблокирован.');
    }


    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);

        return back()->with('success', 'Пользователь успешно разблокирован.');
    }
}


