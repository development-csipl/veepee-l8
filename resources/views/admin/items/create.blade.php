@extends('layouts.admin')
@section('content')

<div class="card m-2">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.item.title_singular') }}
    </div>
    <div class="card-body ">
        <form action="" method="POST" enctype="multipart/form-data"> 
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('states') ? 'has-error' : '' }}">
                        <label for="brand_id">Select Brand<sup class="text-danger">*</sup></label>
                        <select name="brand_id" id="brand_id" class="form-control">
                                <option value="" selected disabled>Select Brand</option>  
                                @foreach($brands as $row) 
                                    <option value="{{$row->id}}"  {{(@old('brand_id') == $row->id) ? 'selected' : ''}}>{{$row->name}}</option>
                                @endforeach
                        </select>
                        @if($errors->has('brand_id'))
                            <p class="help-block">
                                {{ $errors->first('brand_id') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="name">Item Name<sup class="text-danger">*</sup></label>
                        <input type="text" id="name" name="name"  value="{{@old('name')}}" class="form-control" required>
                        @if($errors->has('name'))
                            <p class="help-block">
                                {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('min_range') ? 'has-error' : '' }}">
                        <label for="min_range">Min Range<sup class="text-danger">*</sup></label>
                        <input type="number" id="min_range" name="min_range" class="form-control" value="{{@old('min_range')}}" required>
                        @if($errors->has('min_range'))
                            <p class="help-block">
                                {{ $errors->first('min_range') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('max_range') ? 'has-error' : '' }}">
                        <label for="max_range">Max Range<sup class="text-danger">*</sup></label>
                        <input type="number" id="max_range" name="max_range" value="{{@old('max_range')}}" class="form-control" required>
                        @if($errors->has('max_range'))
                            <p class="help-block">
                                {{ $errors->first('max_range') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                        <label for="category">Category<sup class="text-danger">*</sup></label>
                        <input type="text" id="category" name="category" class="form-control" value="{{@old('category')}}" >
                        @if($errors->has('category'))
                            <p class="help-block">
                                {{ $errors->first('category') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('season') ? 'has-error' : '' }}">
                        <label for="season">Season</label>
                        <input type="text" id="season" name="season" class="form-control" value="{{@old('season')}}" >
                        @if($errors->has('season'))
                            <p class="help-block">
                                {{ $errors->first('season') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="form-group {{ $errors->has('colors') ? 'has-error' : '' }}">
                        <label for="colors">Select Item Color</label>
                         <select name="colors[]" class="form-control select2" multiple="multiple">
                                <option value="" disabled>Select Item Color</option> 
                                @foreach($colors as $row) 
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                        </select>
                        @if($errors->has('colors'))
                            <p class="help-block">
                                {{ $errors->first('colors') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="form-group {{ $errors->has('sizes') ? 'has-error' : '' }}">
                        <label for="sizes">Select Item Size</label>
                         <select name="sizes[]" class="form-control select2" multiple="multiple">
                                <option value="" disabled>Select Item Size</option>  
                                @foreach($sizes as $row) 
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                        </select>
                        @if($errors->has('sizes'))
                            <p class="help-block">
                                {{ $errors->first('sizes') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('article_no') ? 'has-error' : '' }}">
                        <label for="article_no">Article Number</label>
                        <input type="text" id="article_no" name="article_no" value="{{@old('article_no')}}" class="form-control">
                        @if($errors->has('article_no'))
                            <p class="help-block">
                                {{ $errors->first('article_no') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" value="{{@old('quantity')}}" class="form-control">
                        @if($errors->has('quantity'))
                            <p class="help-block">
                                {{ $errors->first('quantity') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('discount') ? 'has-error' : '' }}">
                        <label for="discount">Discount</label>
                        <input type="text" id="discount" name="discount" value="{{@old('discount')}}" class="form-control">
                        @if($errors->has('discount'))
                            <p class="help-block">
                                {{ $errors->first('discount') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                     <a class="btn btn-success float-left" href="{{ route('admin.items.index',['user_id' => $user_id]) }}">
                        {{ trans('global.back_button') }}
                      </a>
                    <input class="btn btn-danger" style="float:right" type="submit" value="{{ trans('global.save') }}">
                </div
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
   
</script>
@stop