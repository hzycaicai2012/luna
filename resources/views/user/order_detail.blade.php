@extends('layouts.base')
@section('js_part')
@endsection
@section('content')
    @if ($valid == 1)
        <div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">付款金额</label>
                    <em class="weui-form-preview__value">¥{{number_format($order->real_fee/100, 2)}}</em>
                </div>
            </div>
            <div class="weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">商品</label>
                    <span class="weui-form-preview__value">{{$order->order_title}}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">详情</label>
                    <span class="weui-form-preview__value">{{$order->order_detail}}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">订单号</label>
                    <span class="weui-form-preview__value">{{$order->order_no}}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">状态</label>
                    @if ($order->status == 0)
                        <span class="weui-form-preview__value">待支付</span>
                    @elseif ($order->status == 1)
                        <span class="weui-form-preview__value">已支付</span>
                    @elseif ($order->status == 2)
                        <span class="weui-form-preview__value">支付失败</span>
                    @else
                        <span class="weui-cell__ft">已取消</span>
                    @endif
                </div>
                @if ($order->status == 1)
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">支付时间</label>
                        <span class="weui-form-preview__value">{{$order->pay_time}}</span>
                    </div>
                @endif
            </div>
            <div class="weui-form-preview__ft">
                <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="/user/orders">返回</a>
            </div>
        </div>
    @else
        <article class="weui-article">
            <section>
                <h2 class="title">啊哦，啥都没有~</h2>
            </section>
        </article>
    @endif
@endsection