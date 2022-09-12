<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('module_id') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control  select-admin-lte" id="status" name="status" required>
                <option value="">-----Select-----</option>
                @foreach (['Approved' => 'Approved', 'Pending' => 'Pending'] as $key => $text)
                    <option value="{{ $key }}" {{ old('status', optional($opinions)->status) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('rating') ? 'has-error' : '' }}">
            <label for="name">Rating (Out of 5)</label>
            <input class="form-control" name="rating" type="number" id="rating"
                value="{{ old('rating', optional($opinions)->rating) }}" min="1" max="5" required>
            {!! $errors->first('rating', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-12">
        <div class="form-group {{ $errors->has('review_summary') ? 'has-error' : '' }}">
            <label for="description">Review Summary</label>
            <textarea
                class="form-control"
                name="review_summary"
                id="review_summary"
                cols="30" rows="5">{{ old('review_summary', optional($opinions)->review_summary) }}</textarea>
        </div>
    </div>
</div>

