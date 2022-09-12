<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ticket_id') ? 'has-error' : '' }}">
            <label for="ticket_id">Ticket</label>
            <select class="form-control  select-admin-lte" id="ticket_id" name="ticket_id" required>
                <option value="">-----Select-----</option>
                @foreach ($tickets as $key => $ticket)
                <option value="{{ $key }}" {{ old('ticket_id', optional($comment)->ticket_id) == $key ? 'selected' : '' }}>
                    {{ $ticket }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('ticket_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
            <label for="user_id">User</label>
            <select class="form-control  select-admin-lte" id="user_id" name="user_id" required>
                <option value="">-----Select-----</option>
                @foreach ($users as $key => $user)
                <option value="{{ $key }}" {{ old('user_id', optional($comment)->user_id) == $key ? 'selected' : '' }}>
                    {{ $user }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
            <label for="message">Message</label>
                    <textarea class="form-control" name="message" cols="50" rows="3" id="message" required>{{ old('message', optional($comment)->message) }}</textarea>

            {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

