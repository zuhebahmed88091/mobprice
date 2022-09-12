<!-- Contact Section Start -->
@if (config('settings.IS_DISPLAY_CONTACT_US'))
    <section id="contact" class="section-padding">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">
                    {{ config('settings.CONTACT_US_HEADER_TITLE') }}
                </h2>
                <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
            </div>
            <div class="row contact-form-area wow fadeInUp" data-wow-delay="0.3s">
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <div class="contact-block">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control"
                                               id="full_name" name="full_name" placeholder="Name" aria-label="full_name"
                                               required data-error="Please enter your name">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Email"
                                               id="email" class="form-control" name="email" aria-label="Email"
                                               required data-error="Please enter your email">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Subject"
                                               id="msg_subject" class="form-control" aria-label="Subject"
                                               required data-error="Please enter your subject">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <textarea class="form-control"
                                              id="message" placeholder="Your Message" aria-label="Message"
                                              rows="7" data-error="Write your message" required></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="submit-button text-left">
                                        <button class="btn btn-success btn-flat" id="form-submit" type="submit">
                                            Send Message
                                        </button>
                                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 col-xs-12">
                    <div class="map">
                        <object style="border:0; height: 100%; width: 100%;"
                                data="{{ config('settings.SITE_MAP_MARKER') }}"></object>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
