<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/4/26
 * Time: 21:27
 */
namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;

class WxPayController extends Controller
{
    public function getPayConfig(Application $wechat)
    {
        $js = $wechat->js;
        return $js->config(array('chooseWXPay'), true);
    }

    public function payCallback() {

    }
}
