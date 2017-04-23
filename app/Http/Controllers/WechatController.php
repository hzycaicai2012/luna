<?php
namespace App\Http\Controllers;
use Log;
use EasyWeChat\Message\Text;

class WechatController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.');
        $wechat = app('wechat');
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
}
