<style>
    #documents {
        background-color: {{ config('settings.ARTICLE_BACKGROUND_COLOR') }};
    }

    #documents .section-title {
        font-family: '{{ config('settings.ARTICLE_HEADER_FONT_FAMILY') }}';
        color: {{ config('settings.ARTICLE_HEADER_TITLE_COLOR') }};
    }

    #products {
        background-color: {{ config('settings.OUR_PROJECT_BACKGROUND_COLOR') }};
    }

    #products .section-title {
        font-family: '{{ config('settings.OUR_PROJECT_HEADER_FONT_FAMILY') }}';
        color: {{ config('settings.OUR_PROJECT_HEADER_TITLE_COLOR') }};
    }

    #services {
        background-color: {{ config('settings.OUR_SERVICE_BACKGROUND_COLOR') }};
    }

    #services .section-title {
        font-family: '{{ config('settings.OUR_SERVICE_HEADER_FONT_FAMILY') }}';
        color: {{ config('settings.OUR_SERVICE_HEADER_TITLE_COLOR') }};
    }

    #teams {
        background-color: {{ config('settings.OUR_SUPPORT_TEAM_BACKGROUND_COLOR') }};
    }

    #teams .section-title {
        font-family: '{{ config('settings.OUR_SUPPORT_TEAM_HEADER_FONT_FAMILY') }}';
        color: {{ config('settings.OUR_SUPPORT_TEAM_HEADER_TITLE_COLOR') }};
    }

    #testimonials {
        background-color: {{ config('settings.TESTIMONIAL_BACKGROUND_COLOR') }};
    }

    #testimonials .section-title {
        font-family: '{{ config('settings.TESTIMONIAL_HEADER_FONT_FAMILY') }}';
        color: {{ config('settings.TESTIMONIAL_HEADER_TITLE_COLOR') }};
    }

    #contact {
        background-color: {{ config('settings.CONTACT_US_BACKGROUND_COLOR') }};
    }

    #contact .section-title {
        font-family: '{{ config('settings.CONTACT_US_HEADER_FONT_FAMILY') }}';
        color: {{ config('settings.CONTACT_US_HEADER_TITLE_COLOR') }};
    }

</style>
