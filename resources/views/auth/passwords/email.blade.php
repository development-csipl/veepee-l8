@extends('layouts.app')
@section('content')
<div class="login-box">
    <div class="login-logo">
        <div class="login-logo">
            <a href="#">
                {{ trans('panel.site_title') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
             @if (session('success'))
            <div class="alert alert-success">
              <p>{{ session('success') }}</p>
            </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning">
                    <p>{{ session('warning') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            <p class="login-box-msg">{{ trans('global.reset_password') }}</p>
            <form method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div>
                    <div class="form-group has-feedback">
                        <input type="email" name="email" class="form-control" required="autofocus" placeholder="{{ trans('global.login_email') }}">
                        @if($errors->has('email'))
                            <p class="help-block">
                                {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            {{ trans('global.reset_password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection