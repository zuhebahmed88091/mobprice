<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">Title</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($affiliate)->title) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
            <label for="domain">Domain</label>
            <input class="form-control" name="domain" type="text" id="domain"
                value="{{ old('domain', optional($affiliate)->domain) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('domain', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

