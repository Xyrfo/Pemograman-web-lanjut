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
            'name' => 'Pelanggan Pertama',
        ];
        UserModel::where('username', 'customer-1')->update($data);

        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }
}
