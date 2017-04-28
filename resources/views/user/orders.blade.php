@extends('layouts.base')
@section('js_part')
@endsection
@section('content')
    <div class="banner">
        <div class="banner-title">一起蹭课吧</div>
    </div>
    <div class="weui-panel weui-panel_access">
        <div class="weui-panel__hd">我的订单</div>
        <div class="weui-panel__bd">
            @foreach ($orders as $order)
                <a href="/user/order/{{$order->order_no}}" class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">{{$order->course_title}}</h4>
                        <p class="weui-media-box__desc">订单号：{{$order->order_no}}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection