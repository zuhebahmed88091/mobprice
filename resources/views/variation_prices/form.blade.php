<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('region_id') ? 'has-error' : '' }}">
            <label for="region_id">Region</label>
            <select class="form-control  select-admin-lte" id="region_id" name="region_id" required>
                <option value="">-----Select-----</option>
                @foreach ($regions as $key => $region)
                <option value="{{ $key }}" {{ old('region_id', optional($variationPrice)->region_id) == $key ? 'selected' : '' }}>
                    {{ $region }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ram_id') ? 'has-error' : '' }}">
            <label for="ram_id">Ram</label>
            <select class="form-control  select-admin-lte" id="ram_id" name="ram_id" required>
                <option value="">-----Select-----</option>
                @foreach ($rams as $key => $ram)
                <option value="{{ $key }}" {{ old('ram_id', optional($variationPrice)->ram_id) == $key ? 'selected' : '' }}>
                    {{ $ram }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('ram_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('storage_id') ? 'has-error' : '' }}">
            <label for="storage_id">Storage</label>
            <select class="form-control  select-admin-lte" id="storage_id" name="storage_id" required>
                <option value="">-----Select-----</option>
                @foreach ($storages as $key => $storage)
                <option value="{{ $key }}" {{ old('storage_id', optional($variationPrice)->storage_id) == $key ? 'selected' : '' }}>
                    {{ $storage }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('storage_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
            <label for="price">Price</label>
            <input class="form-control" name="price" type="number" id="price"
                value="{{ old('price', optional($variationPrice)->price) }}" min="-1000000000000000000" max="1000000000000000000" required step="any">
            {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('usd_price') ? 'has-error' : '' }}">
            <label for="usd_price">Usd Price</label>
            <input class="form-control" name="usd_price" type="number" id="usd_price"
                value="{{ old('usd_price', optional($variationPrice)->usd_price) }}" min="-1000000000000000000" max="1000000000000000000" required step="any">
            {!! $errors->first('usd_price', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----Select-----</option>
                @foreach (['Active' => 'Active',
'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($variationPrice)->status) == $key ? 'selected' : '' }}>
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
        <div class="form-group {{ $errors->has('affiliate_url') ? 'has-error' : '' }}">
            <label for="affiliate_url">Affiliate Url</label>
                    <textarea class="form-control" name="affiliate_url" cols="50" rows="3" id="affiliate_url">{{ old('affiliate_url', optional($variationPrice)->affiliate_url) }}</textarea>

            {!! $errors->first('affiliate_url', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

