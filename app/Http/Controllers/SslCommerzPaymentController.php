<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\StoreAddon;
use Illuminate\Http\Request;
use App\Models\MbTransaction;
use App\Models\PackageOption;
use App\Helpers\CheckoutHelper;
use Illuminate\Support\Facades\Auth;
use App\Library\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{
    /**
     * Call hosted checkout payment option
     *
     * @param Request $request
     * @param bool $isHosted
     * @return void
     */
    public function pay(Request $request, $isHosted = true)
    {
        $cartPrices = CheckoutHelper::calculateCartPrice($request->all());
        if ($cartPrices['paidAmount'] != $request->input('paid_amount')) {
            return back()->with('error_message', 'Invalid paid amount');
        } else if (in_array($request->subscription_type, ['web_plan', 'android_plan', 'ios_plan'])
            && empty($request->setup_id)) {
            return redirect()->route('members.account')->with('error_message', 'Invalid installation package');
        } else if (empty($request->payment_method) || !in_array($request->payment_method, ['bank', 'bKash', 'SSLCommerz'])) {
            return back()->with('error_message', 'Invalid payment method');
        } else if ($request->payment_method == 'bKash') {
            if (empty($request->mb_mobile)
                || empty($request->mb_amount)
                || !is_numeric($request->mb_amount)
                || empty($request->mb_transaction_id)) {
                return back()->with('error_message', 'Invalid mobile transaction details');
            }
        }

        $post_data = array();
        $post_data['total_amount'] = $cartPrices['paidAmount']; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->input('full_name');
        $post_data['cus_email'] = $request->input('email');
        $post_data['cus_add1'] = $request->input('address');
        $post_data['cus_add2'] = '';
        $post_data['cus_city'] = $request->input('city');
        $post_data['cus_state'] = $request->input('state');
        $post_data['cus_postcode'] = $request->input('zip');
        $post_data['cus_country'] = $request->input('country');
        $post_data['cus_phone'] = $request->input('mobile');
        $post_data['cus_fax'] = '';

        # SHIPMENT INFORMATION
        $post_data['shipping_method'] = "NO";
        $post_data['ship_name'] = '';
        $post_data['ship_add1'] = '';
        $post_data['ship_add2'] = '';
        $post_data['ship_city'] = '';
        $post_data['ship_state'] = '';
        $post_data['ship_postcode'] = '';
        $post_data['ship_phone'] = '';
        $post_data['ship_country'] = '';

        # PRODUCT INFORMATION
        $post_data['product_name'] = $request->input('product_name');
        $post_data['product_category'] = "Software";
        $post_data['product_profile'] = 'non-physical-goods';

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        $store_id= null;
        $shoppingCart = [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'address' => $request->input('address'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'zip' => $request->input('zip')
        ];


        if ($request->input('theme')) {
            $shoppingCart['theme'] = $request->input('theme');
        }

        if ($request->input('store_id')) {
            $store_id=$request->input('store_id');
        }

        if ($request->input('domain')) {
            $shoppingCart['domain'] = config('settings.DOMAIN_PRICE');
        }

        if ($request->input('logo')) {
            $shoppingCart['logo'] = config('settings.LOGO_PRICE');
        }

        if ($request->input('ssl')) {
            $shoppingCart['ssl'] = config('settings.SSL_CERTIFICATE_PRICE');
        }

        $package_info="";
        if($request->input('package_info'))
        {
            $package_info=json_decode($request->input('package_info'));
            $shoppingCart['package_id'] = $package_info->id;
            $post_data['product_name']=$post_data['product_name']." Single License";
        }

        if ($request->input('package_options')) {
            $shoppingCart['package_options'] = $request->input('package_options');
        }

        if ($request->input('package_total_price')) {
            $cartPrices['totalAmount']=$request->input('package_total_price');
        }
        

        $subscriptionPlan = 0;
        if ($request->input('subscription_plan')) {
            $subscriptionPlan =$request->input('subscription_plan');
        }

        $monthlyCharge = 0;
        if ($request->subscription_type == 'web' || $request->subscription_type == 'web_plan') {
            $monthlyCharge = config('settings.MONTHLY_CHARGE_WEBSITE');
        } else if ($request->subscription_type == 'android' || $request->subscription_type == 'android_plan') {
            $monthlyCharge = config('settings.MONTHLY_CHARGE_ANDROID_APP');
        } else if ($request->subscription_type == 'ios' || $request->subscription_type == 'ios_plan') {
            $monthlyCharge = config('settings.MONTHLY_CHARGE_IOS_APP');
        }else{
            if($package_info->availability == 'monthly')
            {
                $monthlyCharge=$package_info->base_price;
            }
        }

        // lets build cart for addon here
        if($request->subscription_type == 'addon')
        { 
           $package_options=json_decode($request->package_options);
           $shoppingCart['addons']=[];
           foreach($package_options as $package_option)
           {
                $addonPackage=PackageOption::find($package_option);
                $addon_title = $addonPackage->title;
                $addon_price = $addonPackage->price;
                $addon_id = $addonPackage->id;
                // make array for shopping cart
                $addon_array=["id"=>$addon_id,"title"=>$addon_title,'price'=>$addon_price];
                array_push($shoppingCart['addons'],$addon_array);                

           }

           
        }



        // // Before  going to initiate the payment order status need to insert or update as Pending.
        $order = Order::where('transaction_id', $post_data['tran_id'])
            ->updateOrCreate([
                'user_id' => Auth::user()->id,
                'package_name' => $post_data['product_name'],
                'package_type' => $request->input('subscription_type'),
                'subscription_plan' => $subscriptionPlan,
                'monthly_charge' => $monthlyCharge,
                'shopping_cart' => json_encode($shoppingCart),
                'total_amount' => $cartPrices['totalAmount'],
                'paid_amount' => $cartPrices['paidAmount'],
                'discount' => $cartPrices['discountAmount'],
                'payment_method' => $request->input('payment_method'),
                'status' => 'Pending',
                'transaction_id' => $post_data['tran_id'],
                'setup_id' => $request->input('setup_id'),
                'currency' => $post_data['currency'],
                'store_id' => $store_id
            ]);

        //insert data to store_addon table if we added any addon
          if($request->subscription_type == 'addon')
        { 
            $package_options=json_decode($request->package_options);
            foreach($package_options as $package_option)
            {
                 $addonPackage=PackageOption::find($package_option);
                 $storeAddon= new StoreAddon;
                 $storeAddon->store_id= $store_id;
                 $storeAddon->order_id= $order->id;
                 $storeAddon->addon_id= $addonPackage->id;
                 $storeAddon->title= $addonPackage->title;
                 $storeAddon->price= $addonPackage->price;
                 $storeAddon->save();
            }
        }


        if ($request->input('payment_method') == 'SSLCommerz') {
            // initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payment gateway here )
            $sslCommerzNotification = new SslCommerzNotification();
            if ($isHosted) {
                $response = $sslCommerzNotification->makePayment($post_data, 'hosted');
            } else {
                $response = $sslCommerzNotification->makePayment($post_data, 'checkout', 'json');
            }

            // Log response for further inquire
            Order::where('transaction_id', $post_data['tran_id'])->update(['response' => json_encode($response)]);
        } else if ($request->input('payment_method') == 'bank') {
            if (!empty($order->transaction_id)) {
                return redirect()->route('page', 'success');
            } else {
                return redirect()->route('page', 'fail');
            }
        } else if ($request->input('payment_method') == 'bKash') {
            $mobileTransaction = MbTransaction::create([
                'user_id' => Auth::user()->id,
                'order_id' => $order->id,
                'sender_mobile' => $request->input('mb_mobile'),
                'amount' => $request->input('mb_amount'),
                'transaction_id' => $request->input('mb_transaction_id')
            ]);
            if (!empty($order->transaction_id) && !empty($mobileTransaction->id)) {
                return redirect()->route('page', 'success');
            } else {
                return redirect()->route('page', 'fail');
            }
        }
    }



    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslCommerzNotification = new SslCommerzNotification();

        // Check order status in order table against the transaction id or order id.
        $orderDetails = Order::where('transaction_id', $tran_id)->first();
        if ($orderDetails->status == 'Pending') {
            $validation = $sslCommerzNotification->orderValidate($tran_id, $amount, $currency, $request->all());

            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel.
                Here you need to update order status in order table as Processing or Complete.
                Here you can also sent sms or email for successful transaction to customer
                */
                Order::where('transaction_id', $tran_id)->update(['status' => 'Processing']);

                return redirect()->route('page', 'success')
                    ->with('success_message', 'Transaction is successfully Completed!');
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and
                Transaction validation failed.
                Here you need to update order status as Failed in order table.
                */
                Order::where('transaction_id', $tran_id)->update(['status' => 'Failed']);
                return redirect()->route('page', 'fail')
                    ->with('fail_message', 'validation Fail!');
            }
        } else if ($orderDetails->status == 'Processing' || $orderDetails->status == 'Complete') {
            /*
             That means through IPN Order status already updated.
            Now you can just show the customer that transaction is completed.
            No need to update database.
             */
            return redirect()->route('page', 'success')
                ->with('success_message', 'Transaction is successfully Completed!');
        } else {
            // That means something wrong happened. You can redirect customer to your product page.
            return redirect()->route('page', 'fail')
                ->with('fail_message', 'Invalid Transaction!');
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $orderDetails = Order::where('transaction_id', $tran_id)->first();

        if ($orderDetails->status == 'Pending') {
            Order::where('transaction_id', $tran_id)->update(['status' => 'Failed']);
            return redirect()->route('page', 'fail')->with('fail_message', 'Invalid Transaction!');
        } else if ($orderDetails->status == 'Processing' || $orderDetails->status == 'Complete') {
            return redirect()->route('page', 'success')->with('success_message', 'Transaction is already Successful!');
        } else {
            return redirect()->route('page', 'fail')->with('fail_message', 'Invalid Transaction!');
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $orderDetails = Order::where('transaction_id', $tran_id)->first();

        if ($orderDetails->status == 'Pending') {
            Order::where('transaction_id', $tran_id)->update(['status' => 'Canceled']);
            return redirect()->route('page', 'fail')->with('fail_message', 'Transaction Cancelled!');
        } else if ($orderDetails->status == 'Processing' || $orderDetails->status == 'Complete') {
            return redirect()->route('page', 'success')->with('success_message', 'Transaction is already Successful!');
        } else {
            return redirect()->route('page', 'fail')->with('fail_message', 'Invalid Transaction!');
        }
    }

    public function ipn(Request $request)
    {
        // Received all the payment information from the gateway
        // Check transaction id is posted or not.

        if ($request->input('tran_id')) {

            $tran_id = $request->input('tran_id');

            // Check order status in order table against the transaction id or order id.
            $order_details = Order::where('transaction_id', $tran_id)->first();
            if ($order_details->status == 'Pending') {
                $sslCommerzNotification = new SslCommerzNotification();
                $validation = $sslCommerzNotification->orderValidate(
                    $tran_id,
                    $order_details->amount,
                    $order_details->currency,
                    $request->all()
                );

                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    Order::where('transaction_id', $tran_id)->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                } else {
                    /*
                    That means IPN worked, but Transaction validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    Order::where('transaction_id', $tran_id)->update(['status' => 'Failed']);

                    echo "validation Fail";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
                // That means Order status already updated. No need to update database.
                echo "Transaction is already successfully Completed";
            } else {
                // That means something wrong happened. You can redirect customer to your product page.
                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

    
}
