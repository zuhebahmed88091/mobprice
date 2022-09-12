<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('filter_tab_id') ? 'has-error' : '' }}">
            <label for="filter_tab_id">Filter Tab</label>
            <select class="form-control  select-admin-lte" id="filter_tab_id" name="filter_tab_id" required>
                <option value="">-----Select-----</option>
                @foreach ($filterTabs as $key => $filterTab)
                    <option value="{{ $key }}" {{ old('filter_tab_id', optional($filterSection)->filter_tab_id) == $key ? 'selected' : '' }}>
                        {{ $filterTab }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('filter_tab_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
            <label for="label">Label</label>
            <input class="form-control" name="label" type="text" id="label"
                   value="{{ old('label', optional($filterSection)->label) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('field') ? 'has-error' : '' }}">
            <label for="field">Field</label>
            <input class="form-control" name="field" type="text" id="field"
                   value="{{ old('field', optional($filterSection)->field) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('field', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                @foreach (['Checkbox' => 'Checkbox',
'TableBrands' => 'TableBrands',
'Radio' => 'Radio',
'Slider' => 'Slider'] as $key => $text)
                    <option value="{{ $key }}" {{ old('type', optional($filterSection)->type) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('show_label') ? 'has-error' : '' }}">
            <label for="show_label">Show Label</label>
            <select class="form-control" id="show_label" name="show_label" required>
                @foreach (['Yes' => 'Yes', 'No' => 'No'] as $key => $text)
                    <option value="{{ $key }}" {{ old('show_label', optional($filterSection)->show_label) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('show_label', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('sorting') ? 'has-error' : '' }}">
            <label for="sorting">Sorting</label>
            <input class="form-control" name="sorting" type="number" id="sorting"
                   value="{{ old('sorting', optional($filterSection)->sorting) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('sorting', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Status</label>
            <select class="form-control " id="status" name="status">
                @foreach (['Active' => 'Active', 'Inactive' => 'Inactive'] as $key => $text)
                    <option value="{{ $key }}" {{ old('status', optional($filterSection)->status) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

