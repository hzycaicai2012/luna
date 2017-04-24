<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyWeChat\Foundation\Application;

class TeacherController extends Controller
{
    public function index(Request $request, Application $wechat)
    {
        $user = $request->session()->get('wechat.oauth_user');
        return view('teacher.index', ['user' => $user]);
    }
}
