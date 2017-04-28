<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Foundation\Application;

class TeacherController extends Controller
{
    public function index(Request $request, Application $wechat)
    {
        $user = $request->session()->get('wechat.oauth_user');
        return view('teacher.index', ['user' => $user]);
    }

    public function apply(Request $request) {
        $auth_user = $request->session()->get('wechat.oauth_user');
        $user = DB::table('st_user')->where('open_id', $auth_user->id)->first();
        $apply = DB::table('st_apply')
            ->where('user_id', $user->id)
            ->where('status', '<>', 2)
            ->first();
        if (isset($apply)) {
            return redirect()->route('userHome');
        } else {
            return view('teacher.apply');
        }
    }

    public function submitApply() {
    }
}
