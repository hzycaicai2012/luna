<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/5/12
 * Time: 11:45
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('auth.login');
    }
}
