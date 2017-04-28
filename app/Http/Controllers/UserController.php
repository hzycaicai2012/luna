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

    public function orderList(Request $request)
    {
        $auth_user = $request->session()->get('wechat.oauth_user');
        $user_item = DB::table('st_user')->where('open_id', $auth_user->id)->first();
        $orders = DB::table('st_order')
            ->join('st_course', 'st_course.id', '=', 'st_order.course_id')
            ->select('st_order.*', 'st_course.title as course_title')
            ->where('st_order.user_id', $user_item->id)->get();
        return view('user.orders', ['user' => $user_item, 'orders' => $orders]);
    }

    public function orderDetail(Request $request)
    {
        $order_no = $request->get('order_no');
        $order_item = DB::table('st_order')->where('order_no', $order_no)->first();
        $valid = isset($order_item) ? 1 : 0;
        return view('user.orders', ['order' => $order_item, 'valid' => $valid]);
    }
}
