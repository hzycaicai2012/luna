@extends('layouts.base')
@section('js_part')
    <script>
        function showDialog() {
            $('#custom-dialog').css('display', 'block');
            $('#custom-dialog').css('opacity', '1');
        }
        function hideDialog() {
            $('#custom-dialog').css('display', 'none');
            $('#custom-dialog').css('opacity', '0');
        }
    </script>
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
            <div class="weui-cell__bd" onclick="showDialog()">
                <span style="vertical-align: middle">联系客服</span>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
    </div>
    <div class="js_dialog" id="custom-dialog" style="opacity: 0; display:none;">
        <div class="weui-mask"></div>
        <div class="weui-dialog">
            <div class="weui-dialog__bd">
                <p>长按如下二维码识别或者手动添加客户微信（hzy10080927）</p>
                <img src="/img/qr_code_hzy.jpg" style="width:100%; height: 100%;"/>
            </div>
            <div class="weui-dialog__ft">
                <div id="close-dialog-btn" onclick="hideDialog()" class="weui-dialog__btn weui-dialog__btn_primary">好的</div>
            </div>
        </div>
    </div>
@endsection