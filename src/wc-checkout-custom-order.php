<?php
/**
 * Plugin Name: WooCommerce Checkout Custom Order
 * Plugin URI: https://ahmadsyauqi.com
 * Description: Customize WooCommerce checkout order fields and meta data when an order is placed
 * Version: 0.0.1
 * Author: Ahmad Syauqi
 * Author URI: https://ahmadsyauqi.com
 * Text Domain: wc-checkout-custom-order
 * Requires PHP: 7.1
 * Requires at least: 4.0
 * WC requires at least: 1.0
 * WC tested up to: 8.4
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// The core plugin class
class Checkout_Custom_Order_WooCommerce {

    public function __construct() {
        add_action( 'woocommerce_checkout_process', array( $this, 'custom_checkout_field_process' ) );
        add_action( 'before_woocommerce_init', [$this, 'declare_hpos_compatibility'] );
    }

    public function custom_checkout_field_process() {
        // Check if the billing email is empty
        if ( empty( $_POST['billing_email'] ) ) {

            // Check if phone number is set and prepend the country code if available
            $phone_number = '';
            if ( !empty( $_POST['billing_phone'] ) ) {
                $phone_number = $_POST['billing_phone'];
            }

            // Create the dummy email
            $dummy_email = $phone_number . '.customer@gmail.com';

            // Set the dummy email as the billing email
            $_POST['billing_email'] = $dummy_email;
        }
    }

    public function declare_hpos_compatibility(){
        /**
         * Declare compatibility with WooCommerce HPOS
         * @link https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book
         */
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    
        }
    }
}

new Checkout_Custom_Order_WooCommerce();
