<?php
namespace App\Http\Controllers;

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;

class WechatController extends Controller
{
    public function serve(Application $wechat)
    {
        Log::info('request arrived.');
        $server = $wechat->server;
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return new Text(['content' => '收到文字消息']);
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                default:
                    return '收到其它消息';
                    break;
            }
        });
        Log::info('return response.');
        return $wechat->server->serve();
    }

    public function oAuthCallback(Application $wechat) {
        $oauth = $wechat->oauth;
        $user = $oauth->user();
        $_SESSION['wechat_user'] = $user->toArray();
        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
        header('location:'. $targetUrl);
    }
}
