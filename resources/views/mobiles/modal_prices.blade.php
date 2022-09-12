<div class="row">
    <div class="col-md-8 vl">

        <div class="alert alert-success hidden" id="success-msg-box">
            <span class="glyphicon glyphicon-ok"></span>
            <span id="msgText"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="table-responsive data-table">
            @include('mobiles.table_prices', ['variationPrices' => $variationPrices])
        </div>

    </div>

    <div class="col-md-4">
        <form method="POST" id="formPrice" action="{{ route('mobile.storeVariationPrices') }}">
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('region_id') ? 'has-error' : '' }}">
                        <label for="region_id">Region</label>
                        <select class="form-control select-admin-lte" id="region_id" name="region_id" required>
                            <option value="">-----Select-----</option>

                            @foreach ($regions as $key => $region)
                                <option value="{{ $key }}" {{ old('region_id') == $key ? 'selected' : '' }}>
                                    {{ $region }}
                                </option>
                            @endforeach

                        </select>
                        {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('ram_id') ? 'has-error' : '' }}">
                        <label for="ram_id">Ram</label>
                        <select class="form-control  select-admin-lte" id="ram_id" name="ram_id" required>
                            <option value="">-----Select-----</option>
                            @foreach ($rams as $key => $ram)
                                <option value="{{ $key }}" {{ old('ram_id') == $key ? 'selected' : '' }}>
                                    {{ $ram }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('ram_id', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('storage_id') ? 'has-error' : '' }}">
                        <label for="storage_id">Storage</label>
                        <select class="form-control  select-admin-lte" id="storage_id" name="storage_id" required>
                            <option value="">-----Select-----</option>
                            @foreach ($storages as $key => $storage)
                                <option value="{{ $key }}" {{ old('storage_id') == $key ? 'selected' : '' }}>
                                    {{ $storage }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('storage_id', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                        <label for="price">Price</label>
                        <input class="form-control" name="price" type="number" id="price"
                               value="
                                {{ old('price') }}
                                   " min="-1000000000000000000" max="1000000000000000000" required step="any">
                        {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('usd_price') ? 'has-error' : '' }}">
                        <label for="usd_price">Usd Price</label>
                        <input class="form-control" name="usd_price" type="number" id="usd_price"
                               value="
                                    {{ old('usd_price') }}
                                   " min="-1000000000000000000" max="1000000000000000000" required step="any">
                        {!! $errors->first('usd_price', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('affiliate_url') ? 'has-error' : '' }}">
                        <label for="affiliate_url">Affiliate Url</label>
                        <textarea class="form-control" name="affiliate_url" cols="50" rows="3"
                                  id="affiliate_url">{{ old('affiliate_url') }}</textarea>

                        {!! $errors->first('affiliate_url', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-save pull-right">Add variationPrice</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function () {

        $(".btn-save").click(function () {
            let formPriceObj = $('#formPrice');
            formPriceObj.validate();
            if (formPriceObj.valid()) {
                let Id = $(this).data('id');
                let saveUrl = '{{ route('mobile.storeVariationPrices') }}';
                let data = {
                    'mobile_id': '{{ $mobile->id }}',
                    'region_id': $('#region_id').val(),
                    'ram_id': $('#ram_id').val(),
                    'storage_id': $('#storage_id').val(),
                    'price': $('#price').val(),
                    'usd_price': $('#usd_price').val(),
                    'affiliate_url': $('#affiliate_url').val(),
                    '_token': '{{ csrf_token() }}'
                };

                if (Id) {
                    saveUrl = '{{ route('mobile.updateVariationPrices', ':Id') }}';
                    saveUrl = saveUrl.replace(':Id', Id);
                    data["_method"] = "PUT";
                }

                $.ajax({
                    type: "POST",
                    url: saveUrl,
                    data: data,
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

                            $('#formPrice').trigger("reset");
                            $("#region_id, #ram_id, #storage_id").val('').trigger('change.select2');

                            let btnSave = $('.btn-save');
                            btnSave.html('Add variation Price');
                            btnSave.data('id', '');
                        } else {
                            $('#success-msg-box').addClass('hidden');
                        }
                    }
                });
            }
        });
    })
</script>


