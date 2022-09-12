<div class="row equal">
    @if(count($mobiles) == 0)
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel-body text-center">
                        <h4>No Mobiles Available</h4>
                    </div>
                </div>
            </div>
        </div>
    @else

        @foreach($mobiles as $mobile)
            <div class="col-md-6 col-lg-4 col-sm-12 col-xs-12">

                <div class="box box-default">
                    <div class="box-header with-border" >
                        @if (stripos($mobile->status, 'coming') !== false)
                            <span class="label label-danger">Upcoming</span>
                        @elseif (stripos($mobile->status, 'rumored') !== false)
                            <span class="label label-warning">Rumored</span>
                        @elseif (stripos($mobile->status, 'available') !== false)
                            <span class="label label-success">Available</span>
                        @endif

                         {{-- @if ($article->status == 'Published')
                            <span class="label label-success">{{ $article->status }}</span>
                        @else
                            <span class="label label-warning">{{ $article->status }}</span>
                        @endif  --}}

                        <div class="box-tools pull-right">
                            <form method="POST"
                                  action="{!! route('mobiles.destroy', $mobile->id) !!}"
                                  accept-charset="UTF-8">
                                  <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                @if (App\Helpers\CommonHelper::isCapable('mobiles.quick.update'))
                                <a href="{{ route('mobiles.mobile.edit', [$mobile->id, 'tab'=> "quick-update"] ) }}"
                                        class="btn btn-xs btn-success" title="Quick Update">
                                         <i aria-hidden="true" class="fa fa-fighter-jet"></i>
                                    </a>
                                @endif

                                @if (App\Helpers\CommonHelper::isCapable('mobiles.show'))
                                    <a href="{{ route('mobiles.mobile.edit', [$mobile->id, 'tab'=> "view"] ) }}"
                                        class="btn btn-xs btn-info" title="Show Mobile">
                                         <i aria-hidden="true" class="fa fa-eye"></i>
                                    </a>
                                @endif

                                @if (App\Helpers\CommonHelper::isCapable('mobiles.import_price'))
                                    <a href="{{ route('mobiles.import_price', $mobile->id ) }}"
                                        class="btn btn-xs btn-warning" title="Import Price">
                                         <i aria-hidden="true" class="fa fa-tags"></i>
                                    </a>
                                @endif

                                @if (App\Helpers\CommonHelper::isCapable('mobiles.mobile.edit'))
                                    <a href="{{ route('mobiles.mobile.edit', [$mobile->id, 'tab'=> "edit"] ) }}"
                                        class="btn btn-xs btn-primary" title="Edit Mobile">
                                         <i aria-hidden="true" class="fa fa-pencil"></i>
                                    </a>
                                @endif

                                @if (App\Helpers\CommonHelper::isCapable('mobiles.destroy'))
                                    <button type="submit" class="btn btn-xs btn-danger"
                                        title="Delete Mobile"
                                        onclick="return confirm('Click Ok to delete Mobile.')">
                                      <i aria-hidden="true" class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="left">

                                    <img
                                        src="{{ $mobile->featured_image }}?no-cache={{ time() }}"
                                        alt="{{ $mobile->title }}" style="max-width: 100%; height: auto">
                               </div>
                            </div>
                            <div class="col-md-9">
                                <div class="right">
                                    <div class="row" style="padding-bottom:5px">
                                        <div class="logo1 col-md-1">
                                            <i class="fas fa-mobile-alt" style="font-size:18px; padding-right:2px" ></i>
                                        </div>
                                        <div class="text1 col-md-10">
                                            {{ $mobile->title }}
                                        </div>
                                    </div>

                                    <div class="row" style="padding-bottom:5px">
                                        <div class="logo2 col-md-1">
                                            <i class="fas fa-expand-arrows-alt" style="font-size:18px; padding-right:2px"></i>
                                        </div>
                                        <div class="text2 col-md-10">
                                            {{ $mobile->dimensions }}
                                        </div>
                                    </div>

                                    <div class="row" style="padding-bottom:5px">
                                        <div class="logo3 col-md-1">
                                            <i class="fas fa-memory " style="font-size:18px; padding-right:2px"></i>
                                        </div>
                                        <div class="text3 col-md-10">
                                            {{ $mobile->internal }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="logo4 col-md-1">
                                            <i class="fa fa-microchip" style="font-size:18px; padding-right:2px"></i>
                                        </div>
                                        <div class="text4 col-md-10">
                                            {{ $mobile->cpu }}
                                        </div>
                                    </div>

                                    {{-- <ul style="list-style: none">
                                        <li style="margin-bottom:5px"><i class="fa fa-mobile-phone" style="font-size:34px; margin-right:4px" ></i>  {{ $mobile->title }}</li>
                                        <li style="margin-bottom:5px"><i class="fa-regular fa-mobile" style="font-size:34px; margin-right:4px"></i>{{ $mobile->dimensions }}</li>
                                        <li style="margin-bottom:5px"><i class="fa fa-database" style="font-size:34px; margin-right:4px"></i>{{ $mobile->internal }}</li>
                                        <li style="margin-bottom:5px"><i class="fa fa-microchip" style="font-size:34px; margin-right:4px"></i>{{ $mobile->cpu }} </li>
                                    </ul> --}}

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        @endforeach

    @endif
</div>

@if ($mobiles->total() > 0)
    <div class="row">
        <div class="col-sm-5" style="margin: 30px 0;">
            Showing {{ $mobiles->firstItem() }} to {{ $mobiles->lastItem() }} of {{ $mobiles->total() }}
            entries
        </div>
        <div class="col-sm-7 text-right">
            {{ $mobiles->links('pagination.default') }}
        </div>
    </div>
@endif
