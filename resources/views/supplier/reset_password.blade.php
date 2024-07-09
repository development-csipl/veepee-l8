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
            <p class="login-box-msg">{{ trans('global.reset_password') }}</p>
            <form method="POST" action="">
                {{ csrf_field() }}
                <div>
                    
                    
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" required placeholder="{{ trans('global.login_password') }}">
                        @if($errors->has('password'))
                            <p class="help-block">
                                {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="confirm_password" class="form-control" required placeholder="{{ trans('global.login_password_confirmation') }}">
                        @if($errors->has('confirm_password'))
                            <p class="help-block">
                                {{ $errors->first('confirm_password') }}
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