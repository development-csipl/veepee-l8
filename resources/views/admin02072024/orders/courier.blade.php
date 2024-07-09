@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Add Update Courier Details
    </div>
 
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

           
            <div class="col-md-6 form-group {{ $errors->has('courier_name') ? 'has-error' : '' }}">
                <label for="courier_name">Courier Name</label>
                <input type="text" id="courier_name" name="courier_name" class="form-control" value="{{ @old('courier_name') ?? @$delivery->courier_name }}">
                @if($errors->has('courier_name'))
                    <p class="help-block">
                        {{ $errors->first('courier_name') }}
                    </p>
                @endif
            </div>

             <div class="col-md-6 form-group {{ $errors->has('courier_doc') ? 'has-error' : '' }}">
                <label for="courier_doc">Courier Document</label>
                <input type="file" id="courier_doc" name="courier_doc" class="form-control">
                @if(@$delivery->courier_doc != NULL || '')
                <a href="{{url('images/order_request/'.@$delivery->courier_doc)}}" target="_blank">View Courier Document</a> 
                 
                @endif
                @if($errors->has('courier_doc'))
                    <p class="help-block">
                        {{ $errors->first('courier_doc') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('courier_id') ? 'has-error' : '' }}">
                <label for="courier_id">Courier ID</label>
                <input type="text" id="courier_id" name="courier_id" class="form-control" value="{{ @old('courier_id') ?? @$delivery->courier_id }}">
                @if($errors->has('courier_id'))
                    <p class="help-block">
                        {{ $errors->first('courier_id') }}
                    </p>
                @endif
            </div>

            <div class="col-md-6 form-group {{ $errors->has('courier_date') ? 'has-error' : '' }}">
                <label for="courier_date">Courier Date</label>
                <input type="date" id="courier_date" name="courier_date" class="form-control" value="{{ @old('courier_date') ?? @$delivery->courier_date }}">
                @if($errors->has('courier_date'))
                    <p class="help-block">
                        {{ $errors->first('courier_date') }}
                    </p>
                @endif
            </div>


            </div>


            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-default" href="{{ url()->previous() }}">{{ trans('global.back_to_list') }}</a>
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.save') }}">

                    
                </div>

            </div>
        </form>


    </div>
</div>

<script>
    $('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
   maxDate: '+3m',
   minDate: '+0d'
});

    $('.orderlastdate').datepicker({
    format: 'mm/dd/yyyy',
   maxDate: '+120d',
   minDate: '+7d'
});
</script>
@endsection