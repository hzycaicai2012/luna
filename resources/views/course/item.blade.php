@extends('layouts.base')
@section('content')
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
                    {!!$course->description!!}
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
        <a class="weui-btn weui-btn_primary" href="javascript:" id="pay-course">我要听课（￥9.9）</a>
    </div>
@endsection
