<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('filter_section_id') ? 'has-error' : '' }}">
            <label for="filter_section_id">Filter Section</label>
            <select class="form-control  select-admin-lte" id="filter_section_id" name="filter_section_id" required>
                <option value="">-----Select-----</option>
                @foreach ($filterSections as $key => $filterSection)
                <option value="{{ $key }}" {{ old('filter_section_id', optional($filterOption)->filter_section_id) == $key ? 'selected' : '' }}>
                    {{ $filterSection }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('filter_section_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">Name</label>
            <input class="form-control" name="name" type="text" id="name"
                value="{{ old('name', optional($filterOption)->name) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('value') ? 'has-error' : '' }}">
            <label for="value">Value</label>
            <input class="form-control" name="value" type="text" id="value"
                value="{{ old('value', optional($filterOption)->value) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('value', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                @foreach (['Active' => 'Active', 'Inactive' => 'Inactive'] as $key => $text)
                <option value="{{ $key }}" {{ old('status', optional($filterOption)->status) == $key ? 'selected' : '' }}>
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
        <div class="form-group {{ $errors->has('sorting') ? 'has-error' : '' }}">
            <label for="sorting">Sorting</label>
            <input class="form-control" name="sorting" type="number" id="sorting"
                value="{{ old('sorting', optional($filterOption)->sorting) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('sorting', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

