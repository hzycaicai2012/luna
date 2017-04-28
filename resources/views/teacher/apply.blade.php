@extends('layouts.base')
@section('js_part')
    <script>
        function checkInput() {
            var data = {};
            data['card_id'] = $('#card-id').val();
            data['name'] = $('#name').val();
            data['phone'] = $('#phone').val();
            data['uid'] = $('#uid').val();
            data['skill'] = $('#skill').val();
            data['valid'] = false;
            if (data['card_id'] == null || data['card_id'] == '') {
                alert('请填写身份证号码');
            } else if (data['name'] == null || data['name'] == '') {
                alert('请输入姓名');
            } else if (data['phone'] == null || data['phone'] == '') {
                alert('请输入手机号');
            } else if (data['uid'] == null || data['uid'] == '') {
                alert('请输入微信号');
            } else if (data['skill'] == null || data['skill'] == '') {
                alert('请输入擅长方向')
            } else {
                data['valid'] = true;
            }
            return data;
        }

        function submitApply() {
            var data = checkInput();
            if (data['valid']) {
                $.ajax({
                    type: "POST",
                    url: "/teacher/submitApply",
                    data: data,
                    dataType: 'json',
                    beforeSend: function () {
                        $("#submit-btn").attr({ "disabled": "disabled" });
                    },
                    success: function (res) {
                        if (res.errno != 0) {
                            alert('申请提交失败，请重试');
                        } else {
                            $("#pay-btn").removeAttr("disabled");
                            alert('申请提交成功，请前往个人中心查看审核进度');
                            window.location.href = '/user/homepage';
                        }
                    },
                    fail: function(res) {
                        $("#pay-btn").removeAttr("disabled");
                        alert('申请提交失败，请重试');
                    }
                });
            }
        }
    </script>
@endsection
@section('content')
    <div class="banner">
        <div class="banner-title">一起蹭课吧</div>
    </div>
    <div class="weui-cells__title">基本信息</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">身份证</label></div>
            <div class="weui-cell__bd">
                <input id="card-id" class="weui-input" type="text" pattern="[0-9X]*" placeholder="请输入身份证号码">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
            <div class="weui-cell__bd">
                <input id="name" class="weui-input" type="text" placeholder="请输入真实姓名">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">手机号</label>
            </div>
            <div class="weui-cell__bd">
                <input id="phone" class="weui-input" type="tel" placeholder="请输入手机号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">微信号</label></div>
            <div class="weui-cell__bd">
                <input id="uid" class="weui-input" type="text" placeholder="请输入微信号">
            </div>
        </div>
    </div>
    <div class="weui-cells__title">专业技能信息</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">擅长领域</label></div>
            <div class="weui-cell__bd">
                <input id="skill" class="weui-input" type="text" placeholder="请输入自己擅长的领域方向">
            </div>
        </div>
    </div>
    <div class="footer-btns" style="margin-top:15px;">
        <div id="submit-btn" onclick="submitApply()" class="weui-btn weui-btn_primary">提交申请</div>
    </div>
@endsection