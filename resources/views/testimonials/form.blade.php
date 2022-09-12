<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('customer_id') ? 'has-error' : '' }}">
            <label for="customer_id">Customer</label>
            <select class="form-control  select-admin-lte" id="customer_id" name="customer_id" required>
                <option value="">-----Select-----</option>
                @foreach ($customers as $key => $customer)
                <option value="{{ $key }}" {{ old('customer_id', optional($testimonial)->customer_id) == $key ? 'selected' : '' }}>
                    {{ $customer }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('customer_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('rating') ? 'has-error' : '' }}">
            <label for="rating">Rating</label>
            <input class="form-control" name="rating" type="number" id="rating"
                value="{{ old('rating', optional($testimonial)->rating) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('rating', '<p class="help-block">:message</p>') !!}
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
                <option value="{{ $key }}" {{ old('status', optional($testimonial)->status) == $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
            <label for="message">Message</label>
                    <textarea class="form-control" name="message" cols="50" rows="3" id="message" required>{{ old('message', optional($testimonial)->message) }}</textarea>

            {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

