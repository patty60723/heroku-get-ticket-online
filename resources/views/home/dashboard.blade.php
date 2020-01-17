@extends('layouts.main')

@section('title', $title)

@if (Route::has('login'))
    @auth
        @section('content')
        <div class="row">
            <div class="col-md-3">
                <div class="list-group" role="tablist" id="tabList">
                    <a class="list-group-item list-group-item-action" data-toggle="list" role="tab" href="#m-ticket">團員寄票登記與查詢</a>
                    <a class="list-group-item list-group-item-action" data-toggle="list" role="tab" href="#f-ticket">親友寄票取票</a>
                    <a class="list-group-item list-group-item-action" data-toggle="list" role="tab" href="#e-ticket">線上索票取票</a>
                    <a class="list-group-item list-group-item-action" data-toggle="list" role="tab" href="#statics">統計狀況</a>
                </div>
            </div>
            <div class="col-md-9">
                @include('home.m-ticket', ['instru' => $instru])
            </div>
        </div>
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