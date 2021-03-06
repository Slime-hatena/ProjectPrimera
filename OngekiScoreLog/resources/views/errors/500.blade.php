@extends('layouts.app')

@section('title', '500 Internal Server Error')
@section('hero_title', "500 Internal Server Error")

@section('content')
    <article class="box">
        <div class="error-top">
            <div class="error-top-child">
                <span class="status-code">500</span><br>
                何らかの不具合が発生しました
            </div>
        </div>
        <p>
            表示しようとしたページは何らかの不具合で表示できませんでした。
        </p>
        <p>
            <a href="/">Topページに戻る</a><br>
            <a href="{{url()->previous()}}">元のページに戻る</a><br>
        </p>
    </article>
@endsection