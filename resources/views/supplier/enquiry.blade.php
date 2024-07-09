@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Enquiry
    </div>

    <div class="card-body">
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
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
            

            <div class="col-md-6 form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                <label for="category">Select Category</label>
                <select name="category" class="form-control" required>
                    <option value="" Select>Select Category</option>
                    <option value="Order">Order</option>
                    <option value="Account">Account</option>
                </select>
                @if($errors->has('category'))
                    <p class="help-block">
                        {{ $errors->first('category') }}
                    </p>
                @endif
            </div>
            
            <div class="col-md-6 form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <label for="content">Content</label>
                <input type="text"  id="content" name="content" class="form-control" value="">
                @if($errors->has('content'))
                    <p class="help-block">
                        {{ $errors->first('content') }}
                    </p>
                @endif
            </div>
            

            
            <div class="col-md-6">
                <input class="btn btn-success" type="submit" value="Submit">
            </div>
            </div>
        </form>


    </div>
</div>
@endsection