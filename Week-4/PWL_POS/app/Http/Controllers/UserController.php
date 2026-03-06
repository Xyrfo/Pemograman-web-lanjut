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
        $data = [
            'level_id' => 2,
            'username' => 'manager_tiga',
            'name' => 'Manager 3',
            'password' => Hash::make('12345')   
        ];
        UserModel::create($data);

        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }
}
