@extends('layouts.base')
@section('content')
    <article class="weui-article">
        <h1>{{$course->title}}</h1>
        <section>
            <h2 class="title">课程介绍</h2>
            <section>
                <h3>讲师：{{$course->teacher_name}}</h3>
                <p>
                    {{$course->teacher_desc}}
                </p>
            </section>
            <section>
                <h3>课程内容：</h3>
                <p>
                    {{$course->description}}
                </p>
            </section>
            <section>
                <p>
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
        <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips">我要听课（￥9.9）</a>
    </div>
@endsection