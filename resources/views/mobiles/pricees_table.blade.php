@if(count($variationPrices) == 0)
                                    <div class="panel-body text-center">
                                        <h4>No Variation Prices Available!</h4>
                                    </div>
                                    @else
                                    <div class="table-responsive">
                                        <table id="dataTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Region</th>
                                                <th>Store</th>
                                                <th>Variation</th>
                                                <th class="text-right">Price</th>
                                                <th class="text-right">Usd Price</th>
                                                <th>Status</th>
                                                <th style="min-width:100px;" class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($variationPrices as $variationPrice)
                                            <tr>
                                                <td>{{ $variationPrice->id }}</td>
                                                <td>{{ optional($variationPrice->region)->title }}</td>
                                                <td>{{ $variationPrice->store }}</td>
                                                <td>{{ $variationPrice->variation }}</td>
                                                <td class="text-right">{{ $variationPrice->price }}</td>
                                                <td class="text-right">{{ $variationPrice->usd_price }}</td>
                                                <td>{{ $variationPrice->status }}</td>
                                                <td class="text-center" style="min-width:100px;">
                
                                                    {{-- <form method="POST" action="{!! route('mobile_prices.destroy', $variationPrice->id) !!}"
                                                    accept-charset="UTF-8">
                                                    <input name="_method" value="DELETE" type="hidden">
                                                    {{ csrf_field() }}
                                                        <input type="hidden" name="mobile_id" value="{{ $mobile->id}}"> --}}
                                                        {{-- @if (App\Helpers\CommonHelper::isCapable('variation_prices.edit'))
                                                        <a href="{{ route('variation_prices.edit', $variationPrice->id ) }}"
                                                           class="btn btn-xs btn-primary" title="Edit Variation Price">
                                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                                        </a>
                                                        @endif --}}
                                                        <button type="button" class="btn btn-xs btn-primary btn-edit"
                                                                data-id="{{ $variationPrice->id }}"
                                                                data-region-id="{{ optional($variationPrice->region)->id }}"
                                                                data-store="{{ optional($variationPrice)->store }}"
                                                                data-variation="{{ optional($variationPrice)->variation }}"
                                                                data-price="{{ $variationPrice->price }}"
                                                                data-usd-price="{{ $variationPrice->usd_price }}"
                                                                data-status="{{ $variationPrice->status }}"
                                                                data-affiliate-url="{{ $variationPrice->affiliate_url }}"
                                                                title="Update mobile Price">
                                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                                        </button>
                
                                                        @if (App\Helpers\CommonHelper::isCapable('mobile_prices.destroy'))
                                                            <button type="button" class="btn btn-xs btn-danger btn-price-delete"
                                                                    data-id="{{ $variationPrice->id }}" title="Delete mobile Price">
                                                                <i aria-hidden="true" class="fa fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    {{-- </form> --}}
                
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif


<script>
    
        $(".btn-edit").click(function () {
                let regionObj = $('#region_id');
                let variationObj = $('#variation');
                let priceObj = $('#default_price');
                let storeObj = $('#store');
                let usdPriceObj = $('#usd_price');
                let affiliateUrlObj = $('#affiliate_url');
                let statusObj = $('#priceStatus');
                let btnSave = $('.btn-variation-price-save');

                let Id = $(this).data('id');
                let regionId = $(this).data('region-id');
                let variation = $(this).data('variation');
                let price = $(this).data('price');
                let store = $(this).data('store');
                let usdPrice = $(this).data('usd-price');
                let status = $(this).data('status');
                let affiliateUrl = $(this).data('affiliate-url');

                regionObj.val(regionId).trigger('change');
                priceObj.val(price);
                usdPriceObj.val(usdPrice);
                variationObj.val(variation);
                storeObj.val(store);
                affiliateUrlObj.val(affiliateUrl);
                statusObj.val(status);
                btnSave.html('Update');
                btnSave.data('id', Id);
            });

            

           
            $('.btn-price-delete').click(function () {
                if(confirm("Delete mobile Price?")) {
                    let url = '{{ route('mobile_prices.destroy', ':Id') }}';
                    let Id = $(this).data('id');
                    if (Id) {
                        $.ajax({
                            type: "POST",
                            url: url.replace(':Id', Id),
                            data: {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            dataType: "html",
                            beforeSend: function () {
                                if (loaderImageHtml) {
                                    $('.data-table').html(loaderImageHtml).fadeIn(50);
                                }
                            },
                            success: function (jsonString) {
                                let jsonObject = JSON.parse(jsonString);
                                if (jsonObject.status === 'OK') {
                                    $('#msgText').html(jsonObject.message);
                                    $('#success-msg-box')
                                        .removeClass('hidden')
                                        .fadeIn(1500)
                                        .fadeOut(4500);

                                    $('.data-table').html(jsonObject.html);
                                } else {
                                    $('#success-msg-box').addClass('hidden');
                                }
                            }
                        });
                    }
                }
            });                     
                               
        
   
</script>

                                                                