<form method="POST"
      action="{!! route('event_logs.destroy', $eventLog->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    <a href="{{ route('event_logs.show', $eventLog->id ) }}"
       class="btn btn-xs btn-info" title="Show Event Log">
        <i aria-hidden="true" class="fa fa-eye"></i>
    </a>

    <button type="submit" class="btn btn-xs btn-danger"
            title="Delete Event Log"
            onclick="return confirm('Delete Event Log?')">
        <i aria-hidden="true" class="fa fa-trash"></i>
    </button>
</form>
