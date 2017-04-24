<?php
namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;

class TeacherController extends Controller
{
    public function index(Application $wechat)
    {
        $oauth = $wechat->oauth;
        if (empty($_SESSION['wechat_user'])) {
            $_SESSION['target_url'] = 'teacher/index';
            return $oauth->redirect();

        }
        $user = $_SESSION['wechat_user'];
        return view('teacher.index', ['user' => $user]);
    }
}
