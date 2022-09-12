<?php

namespace App\Helpers;

class CheckoutHelper
{
    public static function itemPrice($key, $value = '', $subscriptionType = 'ios')
    {
        if (empty($value)) {
            return '0.00';
        }

        $totalPrice = 0.00;
        switch ($key) {
            case 'domain':
                $totalPrice = config('settings.DOMAIN_PRICE');
                break;

            case 'logo':
                $totalPrice = config('settings.LOGO_PRICE');
                break;

            case 'ssl':
                $totalPrice = config('settings.SSL_CERTIFICATE_PRICE');
                break;

            case 'payment_gateway':
                if ($value == 'sslcommerz') {
                    $totalPrice = config('settings.SSLCOMMERZ_PRICE');
                } else if ($value == 'aamarpay') {
                    $totalPrice = config('settings.AAMARPAY_PRICE');
                }
                break;

            case 'site_setup_fee':
                $totalPrice = config('settings.SITE_SETUP_FEE');
                break;

            case 'mobile_lifetime_plan':
                $totalPrice = config('settings.MOBILE_APP_LIFETIME_PRICE');
                break;

            case 'web_lifetime_plan':
                $totalPrice = config('settings.WEBSITE_LIFETIME_PRICE');
                break;

            case 'subscription_plan':
                if ($subscriptionType == 'web') {
                    $totalPrice = $value * config('settings.MONTHLY_CHARGE_WEBSITE');
                } else if ($subscriptionType == 'android') {
                    $totalPrice = $value * config('settings.MONTHLY_CHARGE_ANDROID_APP');
                } else if ($subscriptionType == 'ios') {
                    $totalPrice = $value * config('settings.MONTHLY_CHARGE_IOS_APP');
                }
                break;
        }
        return number_format($totalPrice, 2);
    }

    public static function calculateCartPrice($params)
    {
        $totalAmount = 0;
        $discountAmount = 0;
        $setupFee = 0;
        if (!empty($params['subscription_type'])) {
            if ($params['subscription_type'] == 'web') {
                $setupFee = config('settings.SITE_SETUP_FEE');
                if (!empty($params['domain'])) {
                    $setupFee += config('settings.DOMAIN_PRICE');
                }

                if (!empty($params['logo'])) {
                    $setupFee += config('settings.LOGO_PRICE');
                }

                if (!empty($params['ssl'])) {
                    $setupFee += config('settings.SSL_CERTIFICATE_PRICE');
                }

                if (!empty($params['subscription_plan'])) {
                    $totalAmount += config('settings.MONTHLY_CHARGE_WEBSITE') * $params['subscription_plan'];
                    if ($params['subscription_plan'] == 12) {
                        $discountAmount = $setupFee;
                    }
                }
            } else if ($params['subscription_type'] == 'android') {
                $setupFee = config('settings.ANDROID_SETUP_FEE');
                if (!empty($params['subscription_plan'])) {
                    $totalAmount += config('settings.MONTHLY_CHARGE_ANDROID_APP') * $params['subscription_plan'];
                    if ($params['subscription_plan'] == 12) {
                        $discountAmount = $setupFee / 2;
                    }
                }
            } else if ($params['subscription_type'] == 'ios') {
                $setupFee = config('settings.IOS_SETUP_FEE');
                if (!empty($params['subscription_plan'])) {
                    $totalAmount += config('settings.MONTHLY_CHARGE_IOS_APP') * $params['subscription_plan'];
                    if ($params['subscription_plan'] == 12) {
                        $discountAmount = $setupFee / 2;
                    }
                }
            } else if ($params['subscription_type'] == 'mobile_lifetime_plan') {
                $setupFee = config('settings.MOBILE_APP_LIFETIME_PRICE');
            } else if ($params['subscription_type'] == 'web_lifetime_plan') {
                $setupFee = config('settings.WEBSITE_LIFETIME_PRICE');
            } else if ($params['subscription_type'] == 'web_plan') {
                if (!empty($params['subscription_plan'])) {
                    $totalAmount += config('settings.MONTHLY_CHARGE_WEBSITE') * $params['subscription_plan'];
                }
            } else if ($params['subscription_type'] == 'android_plan') {
                if (!empty($params['subscription_plan'])) {
                    $totalAmount += config('settings.MONTHLY_CHARGE_ANDROID_APP') * $params['subscription_plan'];
                }
            } else if ($params['subscription_type'] == 'ios_plan') {
                if (!empty($params['subscription_plan'])) {
                    $totalAmount += config('settings.MONTHLY_CHARGE_IOS_APP') * $params['subscription_plan'];
                }
            }
        }

        $totalAmount += $setupFee;

        return [
            'totalAmount' => $totalAmount,
            'discountAmount' => $discountAmount,
            'paidAmount' => $totalAmount - $discountAmount,
        ];
    }

    public static function cartHtmlPrice($params) {
        $priceList = self::calculateCartPrice($params);
        if ($priceList['discountAmount'] > 0) {
            return '<del>' . number_format($priceList['totalAmount'], 2) . '</del> ' . number_format($priceList['paidAmount'], 2);
        }
        return number_format($priceList['totalAmount'], 2);
    }

    public static function cartPaidAmount($params) {
        $priceList = self::calculateCartPrice($params);
        return $priceList['paidAmount'];
    }
}
