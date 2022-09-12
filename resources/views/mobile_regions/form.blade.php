<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">Title</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($mobileRegion)->title) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('currency') ? 'has-error' : '' }}">
            <label for="currency">Currency</label>
            <input class="form-control" name="currency" type="text" id="currency"
                value="{{ old('currency', optional($mobileRegion)->currency) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('currency', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('iso_code') ? 'has-error' : '' }}">
            <label for="iso_code">Iso Code</label>
            <input class="form-control" name="iso_code" type="text" id="iso_code"
                value="{{ old('iso_code', optional($mobileRegion)->iso_code) }}" minlength="1" maxlength="5" required>
            {!! $errors->first('iso_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('symbol') ? 'has-error' : '' }}">
            <label for="symbol">Symbol</label>
            <input class="form-control" name="symbol" type="text" id="symbol"
                value="{{ old('symbol', optional($mobileRegion)->symbol) }}" minlength="1" maxlength="10" required>
            {!! $errors->first('symbol', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('rate') ? 'has-error' : '' }}">
            <label for="rate">Rate</label>
            <input class="form-control" name="rate" type="number" id="rate"
                value="{{ old('rate', optional($mobileRegion)->rate) }}" min="-99999999" max="99999999" required step="any">
            {!! $errors->first('rate', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----Select-----</option>
                @foreach (['Active' => 'Active',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($mobileRegion)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

