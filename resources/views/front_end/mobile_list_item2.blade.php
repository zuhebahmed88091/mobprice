@if(count($mobiles) == 0)
<div class="col-md-12">
    <div class="box">
        <div class="box-body">
            <div class="panel-body text-center">
                <h4>No Mobiles Found!</h4>
            </div>
        </div>
    </div>
</div>
@else
<ul class="list-unstyled">
    @foreach($mobiles as $mobile)
    <li>
        <a href="{{ $mobile->href }}">
            <div class="media">
                <div class="media-body">
                    <span style="width: 60px;height: 50px;float: left">
                        <img style="margin-right: 12px;float: left;max-width: 55px;max-height: 45px;"
                             src="{{ $mobile->featured_image }}?no-cache={{ time() }}">
                    </span>
                    <h4 class="media-heading">
                        {{$mobile->title}}
                    </h4>
                    <span>From {{$mobile->price}} -
                        @if (stripos($mobile->status, 'coming') !== false)
                                Upcoming
                        @elseif (stripos($mobile->status, 'rumoured') !== false)
                               Rumoured
                        @elseif (stripos($mobile->status, 'available') !== false)
                                Available
                        @endif
                    </span>
                </div>
            </div>
        </a>
    </li>
    @endforeach
</ul>
@endif

