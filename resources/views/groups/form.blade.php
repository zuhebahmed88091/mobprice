<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">Title</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($group)->title) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
            <label for="slug">Slug</label>
            <input class="form-control" name="slug" type="text" id="slug"
                value="{{ old('slug', optional($group)->slug) }}" maxlength="255">
            {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fa_icon') ? 'has-error' : '' }}">
            <label for="fa_icon">Fa Icon</label>
            <input class="form-control" name="fa_icon" type="text" id="fa_icon"
                value="{{ old('fa_icon', optional($group)->fa_icon) }}" maxlength="50">
            {!! $errors->first('fa_icon', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                @foreach (['Active' => 'Active', 'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($group)->status) == $key ? 'selected' : '' }}>
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
        <div class="form-group {{ $errors->has('short_description') ? 'has-error' : '' }}">
            <label for="short_description">Short Description</label>
                    <textarea class="form-control" name="short_description" cols="50" rows="3" id="short_description">{{ old('short_description', optional($group)->short_description) }}</textarea>

            {!! $errors->first('short_description', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

