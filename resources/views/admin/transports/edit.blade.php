@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.transport.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.transports.update", [$transports->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
             <div class="form-group {{ $errors->has('branch_id') ? 'has-error' : '' }}">
                <label for="branch_id">Select Branch*</label>
                <select id="branch_id" name="branch_id" class="form-control" required>
                    <option value="">Select Branch</option>
                    @foreach($branches as $row)
                    <option value="{{$row->id}}" {{ ((@old('branch_id') ?? @$transports->branch_id) == $row->id)  ? 'selected' : '' }}>{{$row->name}}</option>
                    @endforeach
                </select>
               
                @if($errors->has('branch_id'))
                    <p class="help-block">
                        {{ $errors->first('branch_id') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('transport_name') ? 'has-error' : '' }}">
                <label for="transport_name">Transport Name*</label>
                <input type="text" id="transport_name" name="transport_name" class="form-control" required value="{{ old('transport_name', isset($transports) ? $transports->transport_name : '') }}">
                @if($errors->has('transport_name'))
                    <p class="help-block">
                        {{ $errors->first('transport_name') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('gst') ? 'has-error' : '' }}">
                <label for="gst">GST*</label>
                <input type="text" id="gst" name="gst" class="form-control" required  value="{{ old('gst', isset($transports) ? $transports->gst : '') }}">
                @if($errors->has('gst'))
                    <p class="help-block">
                        {{ $errors->first('gst') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">Address*</label>
                <input type="text" id="address" name="address" class="form-control" required  value="{{ old('address', isset($transports) ? $transports->address : '') }}">
                @if($errors->has('address'))
                    <p class="help-block">
                        {{ $errors->first('address') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('contact_person') ? 'has-error' : '' }}">
                <label for="contact_person">Contact Person*</label>
                <input type="text" id="contact_person" name="contact_person" class="form-control" required value="{{ old('contact_person', isset($transports) ? $transports->contact_person : '') }}">
                @if($errors->has('contact_person'))
                    <p class="help-block">
                        {{ $errors->first('contact_person') }}
                    </p>
                @endif
                <p class="helper-block">
                 
                </p>
            </div>

             <div class="form-group {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                <label for="contact_mobile">Contact Mobile*</label>
                <input type="text" id="contact_mobile" name="contact_mobile" class="form-control" required value="{{ old('contact_mobile', isset($transports) ? $transports->contact_mobile : '') }}">
                @if($errors->has('contact_mobile'))
                    <p class="help-block">
                        {{ $errors->first('contact_mobile') }}
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