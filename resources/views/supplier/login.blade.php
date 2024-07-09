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
            <p class="login-box-msg">Sign in to start your session</p>
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
            <form action="{{ route('supplier.login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <input type="text" class="form-control{{ $errors->has('veepeeuser_id') ? ' is-invalid' : '' }}" required autofocus placeholder="Enter Veepee ID" name="veepeeuser_id" value="{{ old('veepeeuser_id', null) }}">
                    @if($errors->has('veepeeuser_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('veepeeuser_id') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}" name="password">
                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>


                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">{{ trans('global.remember_me') }}</label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('global.login') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>



            <p class="mb-1">
                <a class="" href="{{ route('supplier.password.request') }}">
                    {{ trans('global.forgot_password') }}
                </a>
            </p>
            <p class="mb-0">

            </p>
            <p class="mb-1">

            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
@endsection