<form method="POST"
      action="{!! route('opinions.destroy', $opinions->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('opinions.edit'))
        <a href="{{ route('opinions.edit', $opinions->id ) }}"
           class="btn btn-xs btn-primary" title="Edit Permission">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('opinions.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="Delete User Opinions"
                onclick="return confirm('Delete User Opinions?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
