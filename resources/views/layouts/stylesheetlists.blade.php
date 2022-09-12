{{-- <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('themes/innolytic/css/bootstrap.min.css') }}">

<!-- Icon -->
<link rel="stylesheet" href="{{ asset('themes/innolytic/fonts/line-icons.css') }}">

<!-- Owl carousel -->
<link rel="stylesheet" href="{{ asset('themes/innolytic/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('themes/innolytic/css/owl.theme.css') }}">

<!-- Animate -->
<link rel="stylesheet" href="{{ asset('themes/innolytic/css/animate.css') }}">
<!-- Main Style -->
<link rel="stylesheet" href="{{ asset('themes/innolytic/css/main.css') }}">

<!-- Responsive Style -->
<link rel="stylesheet" href="{{ asset('themes/innolytic/css/responsive.css') }}"> --}}

{{-- <!-- Alertify -->
    <link rel="stylesheet" href="{{ asset('alertify/alertify.core.css') }}" />
<link rel="stylesheet" href="{{ asset('alertify/alertify.default.css') }}" />

<!-- Jquery-Typeahead -->
<link rel="stylesheet" href="{{ asset('jquery-typeahead-2.11.0/jquery.typeahead.min.css') }}"> --}}



{{-- js list --}}

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
{{-- <script src="{{ asset('themes/innolytic/js/jquery-min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/popper.min.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/wow.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/jquery.nav.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/scrolling-nav.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/jquery.easing.min.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/jquery.sticky.js') }}"></script>
<script src="{{ asset('themes/innolytic/js/form-validator.min.js') }}"></script>
<script src="{{ asset('jquery-typeahead-2.11.0/jquery.typeahead.min.js') }}"></script>
<script src="{{ asset('alertify/alertify.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script> --}}
{{-- <script src="{{ asset('themes/innolytic/js/main.js') }}"></script> --}}


{{-- scripts --}}

{{-- home blade --}}
{{-- searchInputObj.typeahead({
    minLength: 1,
    maxItem: 0,
    dynamic: true,
    hint: true,
    template: function (query, item) {
        return `{!! view('front_end.article_list_item1') !!}`;
    },
    emptyTemplate: "No results found for <b>@{{query}}</b>",
source: {
articles: {
display: ["title", "details"],
href: "@{{href}}",
templateValue: "@{{title}}",
ajax: function (query) {
return {
type: "GET",
url: '{{ route('articles.searchList') }}',
path: "data.articles",
data: {
q: query
},
callback: {
done: function (data, textStatus, jqXHR) {
// Perform operations on received data...
// IMPORTANT: data has to be returned if this callback is used
return data;
},
fail: function (jqXHR, textStatus, errorThrown) {
},
always: function (data, textStatus, jqXHR) {
},
then: function (jqXHR, textStatus) {
}
}
}
}
}
}
}); --}}