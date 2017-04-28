@extends('layouts.base')
@section('js_part')
@endsection
@section('content')
    <div class="banner">
        <div class="banner-title">一起蹭課吧</div>
    </div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__hd" style="position: relative;margin-right: 10px;">
                <img src="{{$user->avatar}}" style="width: 50px;display: block">
            </div>
            <div class="weui-cell__bd">
                <p>{{$user->nick_name}}</p>
                <p style="font-size: 13px;color: #888888;">摘要信息</p>
            </div>
        </div>
        <div class="blank-line">
        </div>
        <div class="weui-cell weui-cell_access weui-no-border">
            <div class="weui-cell__bd">
                <span style="vertical-align: middle">我的钱包</span>
            </div>
            <div class="weui-cell__ft">0.0</div>
        </div>
        <a class="weui-cell weui-cell_access" href="/user/orders">
            <div class="weui-cell__bd">
                <span style="vertical-align: middle">订单查询</span>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <div class="blank-line">
        </div>
        <div class="weui-cell weui-cell_access weui-no-border">
            <div class="weui-cell__bd">
                <span style="vertical-align: middle">联系客服</span>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
    </div>
@endsection