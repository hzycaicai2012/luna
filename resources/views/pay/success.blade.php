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
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">{{$title}}</h2>
            <p class="weui-msg__desc">{{$detail}}。如有疑问，请<a href="javascript:void(0);" onclick="showDialog()">联系客服</a></p>
        </div>
        <div class="weui-msg__opr-area">
            <p class="weui-btn-area">
                <a href="/course/{{$course_id}}" class="weui-btn weui-btn_primary">返回</a>
            </p>
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