@extends('layouts.admin')
@section('content')
    <div class="card"> 
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger msg-danger">
                            {{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success msg-success">
                            {{ $message }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <form  method="post" action="{{ url('admin/settings/store') }}" >
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Auto Order Cancel </h3>
                            </div>
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <label>Days:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control onlyNumeric" id="days" name="days" value="{{$data['days'] ?? '4' }}" >                                                
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label>Reason:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="reason" name="reason" value="{{$data['reason'] ?? '' }}" >                                                
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <input type="hidden" name="method" value="autocancel" />
                                    <button type="submit" class="btn btn-dark btn-sm " style="float:right">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection