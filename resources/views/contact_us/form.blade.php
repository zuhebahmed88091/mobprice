<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('full_name') ? 'has-error' : '' }}">
            <label for="full_name">Full Name</label>
            <input class="form-control" name="full_name" type="text" id="full_name"
                value="{{ old('full_name', optional($contactUs)->full_name) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('full_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email</label>
            <input class="form-control" name="email" type="text" id="email"
                value="{{ old('email', optional($contactUs)->email) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
            <label for="subject">Subject</label>
            <input class="form-control" name="subject" type="text" id="subject"
                value="{{ old('subject', optional($contactUs)->subject) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
            <label for="message">Message</label>
                    <textarea class="form-control" name="message" cols="50" rows="3" id="message" required>{{ old('message', optional($contactUs)->message) }}</textarea>

            {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('is_read') ? 'has-error' : '' }}">
            <label for="is_read">Is Read</label>
            <div class="checkbox icheck">
				<label for="is_read_1">
					<input id="is_read_1" class="" name="is_read" 
						type="checkbox" value="1" {{ old('is_read', optional($contactUs)->is_read) == '1' ? 'checked' : '' }}>
					Yes
				</label>
			</div>

            {!! $errors->first('is_read', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

