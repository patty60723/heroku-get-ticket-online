@extends('layouts.main')

@section('title', $title)

@if (Route::has('login'))
    @auth
        @section('content')
        <nav>
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group" role="tablist" id="tabList" aria-orientation="vertical">
                        <a class="list-group-item list-group-item-action" data-toggle="list" role="tab" href="#f-ticket">親友寄票取票</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" role="tab" href="#e-ticket">線上索票取票</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" role="tab" href="#statics">統計狀況</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        @include('home.f-ticket', ['instru' => $instru])
                        @include('home.e-ticket')
                    </div>
                </div>
            </div>
        </nav>
        @stop
    @endauth
    @guest
        @section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">使用此功能請先登入</div>
            </div>
        </div>
        @stop
    @endguest
@endif
