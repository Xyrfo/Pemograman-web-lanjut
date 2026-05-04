<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __invoke()
    {
        return 'Nama: Rifo Anggi B D <br> NIM: 244107020063 <br> Kelas: TI-2F';
    }
}
