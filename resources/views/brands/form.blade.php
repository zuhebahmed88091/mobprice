<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">Title</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($brand)->title) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('total_item') ? 'has-error' : '' }}">
            <label for="total_item">Total Item</label>
            <input class="form-control" name="total_item" type="text" id="total_item"
                value="{{ old('total_item', optional($brand)->total_item) }}" min="-32768" max="32767" required>
            {!! $errors->first('total_item', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('sorting') ? 'has-error' : '' }}">
            <label for="sorting">Sorting</label>
            <input class="form-control" name="sorting" type="text" id="sorting"
                value="{{ old('sorting', optional($brand)->sorting) }}" min="-32768" max="32767" required>
            {!! $errors->first('sorting', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('revision') ? 'has-error' : '' }}">
            <label for="revision">Revision</label>
            <input class="form-control" name="revision" type="number" id="revision"
                value="{{ old('revision', optional($brand)->revision) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('revision', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

