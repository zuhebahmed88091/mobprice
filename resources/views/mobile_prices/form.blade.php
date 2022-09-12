<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('mobile_id') ? 'has-error' : '' }}">
            <label for="mobile_id">Mobile</label>
            <select class="form-control  select-admin-lte" id="mobile_id" name="mobile_id" required>
                <option value="">-----Select-----</option>
                @foreach ($mobiles as $key => $mobile)
                <option value="{{ $key }}" {{ old('mobile_id', optional($mobilePrice)->mobile_id) == $key ? 'selected' : '' }}>
                    {{ $mobile }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('mobile_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('region_id') ? 'has-error' : '' }}">
            <label for="region_id">Region</label>
            <select class="form-control  select-admin-lte" id="region_id" name="region_id" required>
                <option value="">-----Select-----</option>
                @foreach ($regions as $key => $region)
                <option value="{{ $key }}" {{ old('region_id', optional($mobilePrice)->region_id) == $key ? 'selected' : '' }}>
                    {{ $region }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('mobile_ram_id') ? 'has-error' : '' }}">
            <label for="mobile_ram_id">Mobile Ram</label>
            <select class="form-control  select-admin-lte" id="mobile_ram_id" name="mobile_ram_id" required>
                <option value="">-----Select-----</option>
                @foreach ($mobileRams as $key => $mobileRam)
                <option value="{{ $key }}" {{ old('mobile_ram_id', optional($mobilePrice)->mobile_ram_id) == $key ? 'selected' : '' }}>
                    {{ $mobileRam }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('mobile_ram_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('mobile_storage_id') ? 'has-error' : '' }}">
            <label for="mobile_storage_id">Mobile Storage</label>
            <select class="form-control  select-admin-lte" id="mobile_storage_id" name="mobile_storage_id" required>
                <option value="">-----Select-----</option>
                @foreach ($mobileStorages as $key => $mobileStorage)
                <option value="{{ $key }}" {{ old('mobile_storage_id', optional($mobilePrice)->mobile_storage_id) == $key ? 'selected' : '' }}>
                    {{ $mobileStorage }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('mobile_storage_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
            <label for="price">Price</label>
            <input class="form-control" name="price" type="number" id="price"
                value="{{ old('price', optional($mobilePrice)->price) }}" min="-1000000000000000000" max="1000000000000000000" required step="any">
            {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('usd_price') ? 'has-error' : '' }}">
            <label for="usd_price">Usd Price</label>
            <input class="form-control" name="usd_price" type="number" id="usd_price"
                value="{{ old('usd_price', optional($mobilePrice)->usd_price) }}" min="-1000000000000000000" max="1000000000000000000" required step="any">
            {!! $errors->first('usd_price', '<p class="help-block">:message</p>') !!}
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
                <option value="{{ $key }}" {{ old('status', optional($mobilePrice)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('affiliate_url') ? 'has-error' : '' }}">
            <label for="affiliate_url">Affiliate Url</label>
                    <textarea class="form-control" name="affiliate_url" cols="50" rows="3" id="affiliate_url">{{ old('affiliate_url', optional($mobilePrice)->affiliate_url) }}</textarea>

            {!! $errors->first('affiliate_url', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

