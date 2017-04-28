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
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . '/';
        $js->setUrl($url);
        // $result = $js->config(array('chooseWxPay'));
        return $js->config(array('chooseWxPay'));
    }

    public function getPaySign(Request $request, Application $wechat) {
        $payment = $wechat->payment;
        $data = array(
            'errno' => 0,
            'data' => [],
            'msg' => '',
        );
        $course_id = $request->input('course_id');
        $phone = $request->input('phone');
        $uid = $request->input('uid');
        $price = intval($request->input('price'));
        $course_item = self::getCourseItem($course_id);
        if (!isset($course_item) || empty($phone) || empty($uid) || $price < 0) {
            $data['errno'] = -1;
            $data['msg'] = '输入参数有误';
            return $data;
        }
        $title = '一起蹭课吧课程费用';
        $detail = '《' . $course_item->title . '》课程费用';
        $user = $request->session()->get('wechat.oauth_user');
        $order_no = self::createOrder($user->id, $course_id, $title, $detail);
        DB::table('st_user')
            ->where('open_id', $user->id)
            ->update(['uid' => $uid, 'phone' => $phone]);
        $attributes = [
            'trade_type'     => 'JSAPI',
            'body'            => $title,
            'detail'          => $detail,
            'out_trade_no'   => $order_no,
            'total_fee'       => $price,
            'openid'          =>  $user->id,
            'notify_url'      => 'http://superfinance.com.cn/course/payCallback'
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        $data = array(
            'errno' => 0,
            'order_no' => $order_no,
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

    public function showPaySuccess(Request $request) {
        $order_no = $request->get('order_no');
        $course_id = $request->get('course_id');
        $order_item = DB::table('st_order')->where('order_no', $order_no)->first();
        $course_item = DB::table('st_course')->where('id', $course_id)->first();
        if (!isset($order_item)) {
            $title = '支付异常';
            $detail = '课程《' . $course_item->title . '》支付出现异常，订单号为：' . $order_no;
            return view('pay.fail', ['title' => $title, 'detail' => $detail, 'course_id' => $course_id]);
        } else {
            $title = '支付成功';
            $detail = '课程《' . $course_item->title . '》支付成功，课程开始前会有客服联系并邀请您进入课程直播群';
            return view('pay.success', ['title' => $title, 'detail' => $detail, 'course_id' => $course_id]);
        }
    }

    public function showPayFail(Request $request) {
        $order_no = $request->get('order_no');
        $course_id = $request->get('course_id');
        $order_item = DB::table('st_order')->where('order_no', $order_no)->first();
        $course_item = DB::table('st_course')->where('id', $course_id)->first();
        if (!isset($order_item)) {
            $title = '支付异常';
            $detail = '课程《' . $course_item->title . '》支付出现异常';
            return view('pay.fail', ['title' => $title, 'detail' => $detail, 'course_id' => $course_id]);
        } else {
            if ($order_item->status == 0) {
                DB::table('st_order')
                    ->where('order_no', $order_item->order_no)
                    ->update(['status' => 2, 'pay_time' => date('Y-m-d H:i:s'), 'updated' => date('Y-m-d H:i:s')]);
            }
            $title = '支付失败';
            $detail = '课程《' . $course_item->title . '》支付失败，请重新提交订单';
            return view('pay.fail', ['title' => $title, 'detail' => $detail, 'course_id' => $course_id]);
        }
    }

    public function showPayCancel(Request $request) {
        $order_no = $request->get('order_no');
        $course_id = $request->get('course_id');
        $order_item = DB::table('st_order')->where('order_no', $order_no)->first();
        $course_item = DB::table('st_course')->where('id', $course_id)->first();
        if (!isset($order_item)) {
            $title = '支付异常';
            $detail = '课程《' . $course_item->title . '》支付出现异常';
            return view('pay.fail', ['title' => $title, 'detail' => $detail, 'course_id' => $course_id]);
        } else {
            if ($order_item->status == 0) {
                DB::table('st_order')
                    ->where('order_no', $order_item->order_no)
                    ->update(['status' => 3, 'pay_time' => date('Y-m-d H:i:s'), 'updated' => date('Y-m-d H:i:s')]);
            }
            $title = '支付取消';
            $detail = '课程《' . $course_item->title . '》支付已取消，请重新提交订单';
            return view('pay.fail', ['title' => $title, 'detail' => $detail, 'course_id' => $course_id]);
        }
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
