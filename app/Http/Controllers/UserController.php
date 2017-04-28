<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/4/28
 * Time: 8:27
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Foundation\Application;

class UserController extends Controller
{
    public function homepage(Request $request)
    {
        $more = isset($request->more) ? intval($request->more) : 0;
        $auth_user = $request->session()->get('wechat.oauth_user');
        $user_item = DB::table('st_user')->where('open_id', $auth_user->id)->first();
        return view('user.homepage', ['user' => $user_item]);
    }
}
