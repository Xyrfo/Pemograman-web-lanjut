<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __invoke($id)
    {
        return "Ini adalah halaman artikel dengan ID: $id";
    }
}
