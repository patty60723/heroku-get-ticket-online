@extends('layouts.main')
@section('content')
<!-- /resources/views/login.blade.php -->
<div class="container mt-40">
    <div class="row">
        <h2>{{ $title }}</h2>
    </div>
    <div class="row justify-content-center">
        <div class="card mt-40 pd-40 w500p">
            @if (old('email'))
                <div class="alert alert-danger text-align-center">Email address or Password invalid</div>
            @endif
            <form action="/login" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror form-control" value="{{ old('email') }}">
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror form-control">
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group text-align-center">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('title', $title)