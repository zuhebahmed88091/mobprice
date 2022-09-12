<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('country_name') ? 'has-error' : '' }}">
            <label for="country_name">Country Name</label>
            <input class="form-control" name="country_name" type="text" id="country_name"
                value="{{ old('country_name', optional($country)->country_name) }}" min="1" max="100" required>
            {!! $errors->first('country_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('country_code') ? 'has-error' : '' }}">
            <label for="country_code">Country Code</label>
            <input class="form-control" name="country_code" type="text" id="country_code"
                value="{{ old('country_code', optional($country)->country_code) }}" min="1" max="2" required>
            {!! $errors->first('country_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('currency_code') ? 'has-error' : '' }}">
            <label for="currency_code">Currency Code</label>
            <input class="form-control" name="currency_code" type="text" id="currency_code"
                value="{{ old('currency_code', optional($country)->currency_code) }}" maxlength="3">
            {!! $errors->first('currency_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('capital') ? 'has-error' : '' }}">
            <label for="capital">Capital</label>
            <input class="form-control" name="capital" type="text" id="capital"
                value="{{ old('capital', optional($country)->capital) }}" maxlength="30">
            {!! $errors->first('capital', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('continent_name') ? 'has-error' : '' }}">
            <label for="continent_name">Continent Name</label>
            <input class="form-control" name="continent_name" type="text" id="continent_name"
                value="{{ old('continent_name', optional($country)->continent_name) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('continent_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('continent_code') ? 'has-error' : '' }}">
            <label for="continent_code">Continent Code</label>
            <input class="form-control" name="continent_code" type="text" id="continent_code"
                value="{{ old('continent_code', optional($country)->continent_code) }}" minlength="1" maxlength="2" required>
            {!! $errors->first('continent_code', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----Select-----</option>
                @foreach (['Active' => 'Active',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($country)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

