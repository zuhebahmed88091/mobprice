<!-- Team Section Start -->
<section id="teams" class="section-padding">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">
                Congratulations!
            </h2>
            <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{{ asset('images/success.png') }}" alt="Order Success" class="success-order">
                <h5>Thank you for your order!</h5>
                <h6>
                    Your order has been placed successfully. Please check your invoice in orders section for details.
                    One of our customer agent will contact with you soon.
                </h6>
                <div style="height: 10px;"></div>
                <a href="{{ route('members.myOrders') }}" class="btn btn-primary btn-flat">Go to Orders</a>
            </div>
        </div>
    </div>
</section>
