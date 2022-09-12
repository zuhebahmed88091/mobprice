<form method="POST"
      action="{!! route('mobiles.destroy', $mobile->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    {{-- <button type="button" class="btn btn-success btn-xs btn-mobile-cost" title="Mobile Price"
            data-toggle="modal"
            data-mobile-id="{{ $mobile->id }}"
            data-mobile-title="{{ optional($mobile->Brand)->title . ' ' .$mobile->title }}"
            data-target="#modalmobilePrices">
        <i aria-hidden="true" class="fa fa-tags"></i>
    </button> --}}

    <a href="{{ route('mobiles.quick.update', $mobile->id ) }}"
       class="btn btn-xs btn-success" title="Quick Update">
        <i aria-hidden="true" class="fa fa-fighter-jet"></i>
    </a>

    <a href="{{ route('mobiles.show', $mobile->id ) }}"
       class="btn btn-xs btn-info" title="Show Mobile">
        <i aria-hidden="true" class="fa fa-eye"></i>
    </a>

    <a href="{{ route('mobiles.import_price', $mobile->id ) }}"
       class="btn btn-xs btn-warning" title="Import Price">
        <i aria-hidden="true" class="fa fa-tags"></i>
    </a>

    <a href="{{ route('mobiles.mobile.edit', $mobile->id ) }}"
       class="btn btn-xs btn-primary" title="Edit Mobile">
        <i aria-hidden="true" class="fa fa-pencil"></i>
    </a>

    <button type="submit" class="btn btn-xs btn-danger"
            title="Delete Mobile"
            onclick="return confirm('Click Ok to delete Mobile.')">
        <i aria-hidden="true" class="fa fa-trash"></i>
    </button>

</form>
