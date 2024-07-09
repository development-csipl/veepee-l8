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
            <p class="login-box-msg">Reset Password</p>
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
            <form action="" method="POST">
                @csrf

                <div class="form-group">
                    <input type="text" class="form-control{{ $errors->has('veepeeuser_id') ? ' is-invalid' : '' }}" required autofocus placeholder="Enter Veepee ID" name="veepeeuser_id" value="{{ old('veepeeuser_id', null) }}">
                    @if($errors->has('veepeeuser_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('veepeeuser_id') }}
                        </div>
                    @endif
                </div>


                <div class="row">
                    
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>



            <p class="mb-1">
                <a class="" href="{{ url('supplier/login') }}">
                   Login
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