<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyWeChat\Foundation\Application;

class TeacherController extends Controller
{
    public function index(Request $request, Application $wechat)
    {
        // $oauth = $wechat->oauth;
        // if (empty($_SESSION['wechat_user'])) {
        //     $_SESSION['target_url'] = 'teacher/index';
        //     return $oauth->redirect();

        // }
        $user = $request->session()->get('wechat.oauth_user');
        return view('teacher.index', ['user' => $user]);
    }
}
