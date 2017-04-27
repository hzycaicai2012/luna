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

        function startPay() {
            var course_id = $('#course-id').val();
            $.ajax({
                type: "POST",
                url: "/wxPay/getPaySign",
                data: {course_id: course_id },
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
                            success: function (res) {
                                alert('success')
                            },
                            cancel: function (res) {
                                alert('failed');
                            }
                        });
                    }
                }
            });
        }
        // $('#pay-btn').click(startPay)
    </script>
@endsection
@section('content')
    <input type="hidden" value="{{$course->id}}" id="course-id">
    <div class="banner">
        <div class="banner-title">一起蹭課吧</div>
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
    <label for="weuiAgree" class="weui-agree">
        <input id="weuiAgree" type="checkbox" class="weui-agree__checkbox">
            <span class="weui-agree__text">
                阅读并同意<a href="javascript:void(0);">《用户协议》</a>
            </span>
    </label>
    <div class="weui-btn-area">
        <div class="weui-btn weui-btn_primary" onclick="startPay()" id="pay-btn">我要听课（￥9.9）</div>
    </div>
@endsection
