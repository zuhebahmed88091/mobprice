@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote.css') }}">
@endsection

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">Title</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($news)->title) }}" minlength="1" maxlength="250" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----Select-----</option>
                @foreach (['Active' => 'Active',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($news)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
            <label for="image">Thumbnail </label>
            <div class="input-group">
                @if(!empty($news))
                    <label class="input-group-btn">
                        <img src="{{ asset('storage/news/' . optional($news)->image ) }}"
                             alt="Image"
                             style="width: 34px; height: 34px;margin-right: 10px;">
                    </label>
                @endif
                <input value="{{ !empty($news) ?  optional($news)->image : '' }}"
                       type="text"
                       id="image"
                       class="form-control" readonly required>
                <label class="input-group-btn">
                    <span class="btn btn-warning">
                        Browse&hellip; <input type="file" name="image" style="display: none;">
                    </span>
                </label>
            </div>
            {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('short_description') ? 'has-error' : '' }}">
            <label for="short_description">Short Description</label>
            <input class="form-control" name="short_description" type="text" id="short_description"
                value="{{ old('short_description', optional($news)->short_description) }}" required>
            {!! $errors->first('short_description', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="form-group">
    <label for="description" class="control-label ">Description</label>
    <textarea class="form-control rich-textarea" aria-label="description" name="description"
                  cols="50"
                  rows="5" id="description">
                  {{ old('description', optional($news)->description) }}</textarea>
</div>


@section('javascript')
    <script src="{{ asset('vendor/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('vendor/summernote/summernote.settings.js') }}"></script>
@endsection

