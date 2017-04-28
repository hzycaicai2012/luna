<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/4/25
 * Time: 1:41
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Foundation\Application;

class CourseController extends Controller
{
    public function courseList(Request $request)
    {
        $more = isset($request->more) ? intval($request->more) : 0;
        $user = $request->session()->get('wechat.oauth_user');
        $course_selection = DB::table('st_course')
            ->join('st_teacher', 'st_course.teacher_id', '=', 'st_teacher.id')
            ->select('st_course.*', 'st_teacher.name as teacher_name',
                'st_teacher.description as teacher_desc', 'st_teacher.skill');
        $courses = array();
        if ($more > 0) {
            $courses = $course_selection->get();
        } else {
            $courses = $course_selection->take(3)->get();
        }
        return view('course.list', ['courses' => $courses, 'more' => $more]);
    }

    public function item(Request $request, Application $wechat, $id) {
        $auth_user = $request->session()->get('wechat.oauth_user');
        $js = $wechat->js;
        $course = DB::table('st_course')
            ->join('st_teacher', 'st_course.teacher_id', '=', 'st_teacher.id')
            ->where('st_course.id', $id)
            ->select('st_course.*', 'st_teacher.name as teacher_name',
                'st_teacher.description as teacher_desc', 'st_teacher.skill')
            ->first();
        $user = DB::table('st_user')->where('open_id', $auth_user->id)->first();
        $need_fill = (empty($user->phone) || empty($user->uid));
        return view('course.item',
            ['course' => $course, 'js_config' =>  $js->config(array('chooseWxPay'), true),
                'user' => $user, 'need_fill' => $need_fill]);
    }

    public function payCallback(Application $wechat) {
        Log::info('pay callback request arrived.');
        $response = $wechat->payment->handleNotify(function($notify, $successful){
            Log::info('pay callback order_no is:' . $notify->out_trade_no);
            $order_item = DB::table('st_order')->where('order_no', $notify->out_trade_no)->first();
            if (!isset($order_item)) {
                Log::info('pay callback order is not exist');
                return 'Order not exist.';
            }
            if ($order_item->status != 0) {
                Log::info('pay callback order is payed, status is' . $order_item->status);
                return true;
            }
            if ($successful) {
                Log::info('pay callback order is pay success');
                DB::table('st_order')
                    ->where('order_no', $notify->out_trade_no)
                    ->update(['pay_time' => date('Y-m-d H:i:s'), 'status' => 1]);
            } else {
                Log::info('pay callback order is pay failed');
                DB::table('st_order')
                    ->where('order_no', $notify->out_trade_no)
                    ->update(['status' => 2]);
            }
            return true;
        });
        return $response;
    }


}
