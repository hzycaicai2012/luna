<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/4/26
 * Time: 21:27
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;

class WxPayController extends Controller
{
    public function getPayConfig(Application $wechat)
    {
        $js = $wechat->js;
        return $js->config(array('chooseWXPay'), true);
    }

    public function payCallback(Application $wechat) {
        $response = $wechat->payment->handleNotify(function($notify, $successful){
            $order_item = DB::table('st_order')->where('order_no', $notify->out_trade_no)->first();
            if (!isset($order_item)) {
                return 'Order not exist.';
            }
            if ($order_item->status != 0) {
                return true;
            }
            if ($successful) {
                DB::table('st_order')
                    ->where('order_no', $notify->out_trade_no)
                    ->update(['pay_time' => date('Y-m-d H:i:s'), 'status' => 1]);
            } else {
                DB::table('st_order')
                    ->where('order_no', $notify->out_trade_no)
                    ->update(['status' => 2]);
            }
            return true;
        });
        return $response;
    }

    public function getPaySign(Request $request, Application $wechat) {
        $payment = $wechat->payment;
        $data = array(
            'errno' => 0,
            'data' => [],
            'msg' => '',
        );
        $course_id = $request->input('course_id');
        $course_item = self::getCourseItem($course_id);
        if (!isset($course_item)) {
            $data['errno'] = -1;
            $data['msg'] = '输入参数有误';
            return $data;
        }
        $title = '一起蹭课吧课程费用';
        $detail = '课程《' . $course_item->title . '》课程费用￥9.9元';
        $user = $request->session()->get('wechat.oauth_user');
        $order_no = self::createOrder($user->id, $course_id, $title, $detail);
        $attributes = [
            'trade_type'     => 'JSAPI',
            'body'            => $title,
            'detail'          => $detail,
            'out_trade_no'   => $order_no,
            'total_fee'       => 1,
            'openid'          =>  $user->id,
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        $data = array(
            'errno' => 0,
            'data' => [],
            'msg' => '',
        );
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS')
        {
            $prepayId = $result->prepay_id;
            $config = $payment->configForJSSDKPayment($prepayId);
            $data['data'] = $config;
        } else {
            $data['errno'] = -1;
            $data['msg'] = '生成订单错误！';
        }
        return $data;
    }

    private function createOrder($open_id, $course_id, $title, $detail) {
        $user_item = DB::table('st_user')->where('open_id', $open_id)->first();
        if (!isset($user_item)) {
            return null;
        } else {
            $order_no = (sprintf("%016d",microtime(true) * 10000) . sprintf("%010d", mt_rand()));
            $id = DB::table('st_order')->insertGetId(
                [
                    'order_no' => $order_no, 'user_id' => $user_item->id,
                    'course_id' => $course_id, 'order_title' => $title,
                    'order_detail' => $detail, 'status' => 0,
                    'created' => date('Y-m-d H:i:s'), 'updated' => date('Y-m-d H:i:s'),
                ]
            );
            return $order_no;
        }
    }

    private function getCourseItem($course_id) {
        $course_item = DB::table('st_course')->where('id', $course_id)->first();
        return $course_item;
    }
}
