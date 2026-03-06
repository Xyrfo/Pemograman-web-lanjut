<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Hash;
use Illuminate\Http\Request;
use User;

class UserController extends Controller
{
    public function profile($id, $name)
    {
        return view('products.user', ['id' => $id, 'name' => $name]);
    }

    public function index()
    {
        $user = UserModel::create(
            [
                'username' => 'manager13',
                'name' => 'Manager13',
                'password' => Hash::make('12345'),
                'level_id' => 2,
            ]
        );

        $user->username = 'manager14';

        $user->save();

        $user->wasChanged(); // true
        $user->wasChanged('username'); // true
        $user->wasChanged('username', 'level_id'); // true
        $user->wasChanged('name'); // false
        dd($user->wasChanged('name', 'username')); // true


        return view('user', ['data' => $user]);
    }
}
