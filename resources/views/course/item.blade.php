@extends('layouts.base')
@section('js_part')
    <script>
//        $.ajax({
//            type: "GET",
//            url: "/wxPay/getPayConfig",
//            beforeSend: function () {
//                $("#pay-btn").attr({ "disabled": "disabled" });
//            },
//            success: function (data) {
//                $("#pay-btn").removeAttr("disabled");
//                window.wx.config({
//                    debug: true,
//                    appId: data.appId,
//                    timestamp: (data.timestamp + ''),
//                    nonceStr: data.nonceStr,
//                    signature: data.signature,
//                    jsApiList: ["chooseWXPay"]
//                });
//                console.log('wx config success, data is:' + data);
//                window.wx.ready(function () {
//                });
//                window.wx.error(function (res) {
//                });
//            }
//        });
        wx.config({!! $js_config !!});

        function checkInput() {
            var phone = $('#user-phone').val();
            var uid = $('#user-uid').val();
            var agree = $('#user-agree').prop('checked');
            var user_data = {valid: false, phone : phone, uid: uid, agree: agree};
            if (phone == null || phone == '') {
                alert('请填写手机号');
            } else if (uid == null || uid == '') {
                alert('请填写微信号');
            } else if (!agree) {
                alert('请先同意用户协议');
            } else {
                user_data['valid'] = true;
            }
            return user_data;
        }

        function startPay() {
            var user_input = checkInput();
            if (user_input.valid) {
                console.log(user_input);
                var price = $('#course-price').val();
                console.log(price);
                var course_id = $('#course-id').val();
                $.ajax({
                    type: "POST",
                    url: "/wxPay/getPaySign",
                    data: {course_id: course_id, uid: user_input.uid, phone: user_input.phone, price: price },
                    dataType: 'json',
                    beforeSend: function () {
                        $("#btnPay").attr({ "disabled": "disabled" });
                    },
                    success: function (res) {
                        if (res.errno != 0) {
                            alert(res.msg);
                        } else {
                            $("#btnPay").removeAttr("disabled");
                            wx.chooseWXPay({
                                timestamp: res.data.timestamp,
                                nonceStr: res.data.nonceStr,
                                package: res.data.package,
                                signType: res.data.signType,
                                paySign: res.data.paySign,
                                success: function (ret) {
                                    if(ret.errMsg == "chooseWXPay:ok" ){
                                        window.location.href = '/wxPay/paySuccess?order_no=' + res.order_no + '&course_id=' + course_id;
                                    } else {
                                        window.location.href = '/wxPay/payFail?order_no=' + res.order_no + '&course_id=' + course_id;
                                    }
                                },
                                cancel: function (ret) {
                                    window.location.href = '/wxPay/payCancel?order_no=' + res.order_no + '&course_id=' + course_id;
                                }
                            });
                        }
                    }
                });
            }
        }

        function updateOrderStatus(order_no, status) {
            $.ajax({
                type: "POST",
                url: "/order/updateOrder",
                data: {order_no: order_no, status: status},
                dataType: 'json',
                success: function (res) {
                }
            });
        }
        // $('#pay-btn').click(startPay)
    </script>
@endsection
@section('content')
    <input type="hidden" value="{{$course->id}}" id="course-id">
    <div class="banner">
        <div class="banner-title">一起蹭课吧</div>
    </div>
    <article class="weui-article">
        <h1>{{$course->title}}</h1>
        <section>
            <section>
                <h3>
                    讲师介绍：<hr>
                </h3>
                <p class="">
                    本次的讲师是{{$course->teacher_name}}，{{$course->teacher_desc}}
                </p>
            </section>
            <section>
                <h3>课程内容：<hr></h3>
                <p>
                    {!! $course->description !!}
                </p>
            </section>
            <section>
                <p class="grey-content small-content">
                    时间：{{$course->start_time}}
                </p>
            </section>
        </section>
    </article>
    @if ($need_fill)
    <div class="weui-cells__title">请先填写如下信息，方便客服与您联系</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">微信号</label></div>
            <div class="weui-cell__bd">
                <input id="user-uid" class="weui-input" placeholder="请输入微信号">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">手机号</label>
            </div>
            <div class="weui-cell__bd">
                <input id="user-phone" class="weui-input" type="tel" placeholder="请输入手机号">
            </div>
        </div>
    </div>
    @else
        <input id="user-phone" class="weui-input" type="hidden" value="{{$user->phone}}">
        <input id="user-uid" class="weui-input" type="hidden" value="{{$user->uid}}">
    @endif
    <label for="weuiAgree" class="weui-agree">
        <input id="user-agree" type="checkbox" class="weui-agree__checkbox">
            <span class="weui-agree__text">
                阅读并同意<a href="javascript:void(0);">《用户协议》</a>
            </span>
    </label>
    <input type="hidden" id="course-price" value="{{$course->price}}">
    <div class="weui-btn-area">
        <div class="weui-btn weui-btn_primary" onclick="startPay()" id="pay-btn">我要听课（￥{{$course->show_price}}）</div>
    </div>
@endsection
