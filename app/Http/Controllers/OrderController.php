<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/4/28
 * Time: 16:10
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Foundation\Application;

class OrderController extends Controller
{
    public function updateOrder(Request $request)
    {
        $order_no = $request->input('order_no');
        $status = intval($request->input('status'));
        Log::info('update order from wxPayCallback, order_no:' . $order_no . ', status:' . $status);
        if (empty($order_no) || empty($status)) {
            return array('errno' => -1, 'msg' => '参数有误');
        }
        $order_item = DB::table('st_order')->where('order_no', $order_no)->first();
        if ($order_item->status == 0) {
            DB::table('st_order')
                ->where('order_no', $order_item->order_no)
                ->update(['status' => $status, 'pay_time' => date('Y-m-d H:i:s'), 'updated' => date('Y-m-d H:i:s')]);
        }
        return array('errno' => 0, 'msg' => '');
    }
}
