@if(count($variationPrices) == 0)
    <div class="panel-body text-center">
        <h4>No Prices Available!</h4>
    </div>
@else
    <table id="dataTable" class="table table-bordered table-striped">
        <thead>

        <tr>
            <th>Id</th>
            <th>Region</th>
            <th>Ram</th>
            <th>Storage</th>
            <th class="text-right">Price</th>
            <th class="text-right">Usd Price</th>
            <th style="min-width:100px;" class="text-center">Action</th>
        </tr>

        </thead>
        <tbody>
        @php($serial = 1)
        @foreach($variationPrices as $variationPrice)

            <tr>
                <td>{{ $serial++ }}</td>
                <td>{{ optional($variationPrice->region)->title }}</td>
                <td>{{ optional($variationPrice->ram)->title }}</td>
                <td>{{ optional($variationPrice->storage)->title }}</td>
                <td class="text-right">{{ $variationPrice->price }}</td>
                <td class="text-right">{{ $variationPrice->usd_price }}</td>
                <td class="text-center">

                    <button type="button" class="btn btn-xs btn-primary btn-edit"
                            data-id="{{ $variationPrice->id }}"
                            data-region-id="{{ optional($variationPrice->region)->id }}"
                            data-ram-id="{{ optional($variationPrice->ram)->id }}"
                            data-storage-id="{{ optional($variationPrice->storage)->id }}"
                            data-price="{{ $variationPrice->price }}"
                            data-usd-price="{{ $variationPrice->usd_price }}"
                            data-affiliate-url="{{ $variationPrice->affiliate_url }}"
                            title="Update mobile Price">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </button>
                    @if (App\Helpers\CommonHelper::isCapable('mobile.destroyVariationPrices'))
                        <button type="button" class="btn btn-xs btn-danger btn-delete"
                                data-id="{{ $variationPrice->id }}" title="Delete mobile Price"
                                onclick="return confirm('Delete mobile Price?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </td>
            </tr>

        @endforeach
        </tbody>
    </table>
@endif

<script>
    $(document).ready(function () {
        $(".btn-edit").click(function () {
            let regionObj = $('#region_id');
            let ramObj = $('#ram_id');
            let storageObj = $('#storage_id');
            let priceObj = $('#price');
            let usdPriceObj = $('#usd_price');
            let affiliateUrlObj = $('#affiliate_url');
            let btnSave = $('.btn-save');

            let Id = $(this).data('id');
            let regionId = $(this).data('region-id');
            let ramId = $(this).data('ram-id');
            let storageId = $(this).data('storage-id');
            let price = $(this).data('price');
            let usdPrice = $(this).data('usd-price');
            let affiliateUrl = $(this).data('affiliate-url');

            regionObj.val(regionId).trigger('change');
            ramObj.val(ramId).trigger('change');
            storageObj.val(storageId).trigger('change');
            priceObj.val(price);
            usdPriceObj.val(usdPrice);
            affiliateUrlObj.val(affiliateUrl);

            btnSave.html('Update');
            btnSave.data('id', Id);
        });

        $(".btn-delete").click(function () {
            let url = '{{ route('mobile.destroyVariationPrices', ':Id') }}';
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
        });
    })
</script>
