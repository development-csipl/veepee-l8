@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.station.title_singular') }}
    </div>
  
    <div class="card-body">
        <form action="{{ route("admin.station.update", [$station->id]) }}" method="POST" enctype="multipart/form-data"> 
            @csrf

            <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }}">
                <label for="country_id">Select Country*</label>
                <select id="country_id" name="country_id" class="form-control" required>
                    <option value="">Select Country</option>
                    @foreach($countries as $row)
                    <option value="{{$row->id}}" {{ ($station->country_id == $row->id) ? 'selected' : '' }}>{{$row->country}}</option>
                    @endforeach
                   
                </select>
                @if($errors->has('country_id'))
                    <p class="help-block">
                        {{ $errors->first('country_id') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>

            <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }}">
                <label for="state_id">Select State*</label>
                <select id="state_id" name="state_id" id="state_id" class="form-control" required>
                    <option value="">Select State</option>
                    @foreach($states as $row)
                    <option value="{{$row->id}}" {{ ($station->state_id == $row->id) ? 'selected' : '' }}>{{$row->state_name}}</option>
                    @endforeach
                    
                </select>
                @if($errors->has('state_id'))
                    <p class="help-block">
                        {{ $errors->first('state_id') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>


            <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                <label for="city_id">Select City*</label>
                <select id="city_id" name="city_id" class="form-control" required>
                    <option value="">Select City</option>
                    @foreach($cities as $row)
                    <option value="{{$row->id}}" {{ ($station->city_id == $row->id) ? 'selected' : '' }}>{{$row->city_name}}</option>
                    @endforeach
                </select>
                @if($errors->has('city_id'))
                    <p class="help-block">
                        {{ $errors->first('city_id') }}
                    </p>
                @endif
                <p class="helper-block">
                   
                </p>
            </div>

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script>
CKEDITOR.replace('description');
</script>
<script>
    Dropzone.options.photoDropzone = {
    url: '{{ route('admin.product-categories.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4086,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="photo"]').remove()
      $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($productCategory) && $productCategory->photo)
      var file = {!! json_encode($productCategory->photo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@stop