<?php
if (!defined('ABSPATH')) exit();



class AppSumoRequestRest
{
    function __construct()
    {
        add_action('rest_api_init', array($this, 'register_routes'));
    }


    public function register_routes()
    {


        register_rest_route(
            'license-manager/v2',
            '/appsumo_webhooks',
            array(
                'methods'  => 'GET',
                'callback' => array($this, 'appsumo_webhooks'),
                'permission_callback' => '__return_true',
            )
        );
    }

    /**
     * Return _post_meta
     *
     * @since 1.0.0
     * @param WP_REST_Request $post_data Post data.
     */
    public function appsumo_webhooks($post_data)
    {


        $response = array();
        $event = isset($post_data['event']) ?  sanitize_text_field($post_data['event']) : '';
        $license_key = isset($post_data['license_key']) ? sanitize_text_field($post_data['license_key']) : '';
        $prev_license_key = isset($post_data['prev_license_key']) ? sanitize_text_field($post_data['prev_license_key']) : '';
        $plan_id = isset($post_data['plan_id']) ? sanitize_text_field($post_data['plan_id']) : '';
        $license_status = isset($post_data['license_status']) ? sanitize_text_field($post_data['license_status']) : '';
        $event_timestamp = isset($post_data['event_timestamp']) ? sanitize_text_field($post_data['event_timestamp']) : '';
        $created_at = isset($post_data['created_at']) ? sanitize_text_field($post_data['created_at']) : '';
        $tier = isset($post_data['tier']) ? sanitize_text_field($post_data['tier']) : '';
        $test = isset($post_data['test']) ? sanitize_text_field($post_data['test']) : '';
        $extra = isset($post_data['extra']) ? sanitize_text_field($post_data['extra']) : [];



        $user_email = isset($post_data->user_email) ?  sanitize_text_field($post_data->user_email) : '';
        $user_name = isset($post_data->user_name) ?  sanitize_text_field($post_data->user_name) : '';




        if (empty($event)) {
            $response['message'] = 'Somthing Missing';
            error_log(serialize($response));
            echo json_encode($response);
            exit(0);
        }



        if ($event == 'purchase') {



            $class_license_manager_manage_license = new class_license_manager_manage_license();


            $args['license_key'] = $license_key;

            $args['license_status'] = 'pending';

            $args['domains_list'] = '';
            //$args['license_email'] = $billing_email;
            // $args['user_id'] = $user_id;
            //$args['order_id'] = $order_id;

            $args['date_created'] = date('Y-m-d');
            $args['date_renewed'] = date('Y-m-d');
            $date_expiry = date('Y-m-d', strtotime('+100 years'));

            $args['date_expiry'] = $date_expiry;

            $args['meta_data'] = array();
            $create_license = $class_license_manager_manage_license->create_license($args);

            $message = 'License Created';

            //$response['order_id'] = $order_id;
            $response['success'] = true;
            $response['event'] = $event;
            $response['message'] = $message;


            die(wp_json_encode($response));
        }
        if ($event == 'activate') {


            if ($tier == 1) {
                $product_id = 3814;
                $variation_id = 37943;
                $domain_count = 5;
            }
            if ($tier == 2) {
                $product_id = 3814;
                $variation_id = 93970;
                $domain_count = 10;
            }
            if ($tier == 3) {
                $product_id = 3814;
                $variation_id = 37944;
                $domain_count = 9999;
            }


            $class_license_manager_manage_license = new class_license_manager_manage_license();

            //$LicenseManagerWoocommerce = new LicenseManagerWoocommerce();

            $args['product_id'] = $product_id;
            $args['variation_id'] = $variation_id;
            $args['license_key'] = $license_key;
            $args['license_status'] = 'active';
            $args['domain_count'] = $domain_count;

            $update_license = $class_license_manager_manage_license->update_license($args);

            $message = 'License activated';

            //$response['order_id'] = $order_id;
            $response['success'] = true;
            $response['event'] = $event;
            $response['message'] = $message;


            die(wp_json_encode($response));
        }

        if ($event == 'deactivate') {



            $class_license_manager_manage_license = new class_license_manager_manage_license();

            //$LicenseManagerWoocommerce = new LicenseManagerWoocommerce();



            $args['license_key'] = $license_key;
            $args['license_status'] = 'deactivate';

            $update_license = $class_license_manager_manage_license->update_license($args);

            $message = 'License deactivate done';

            //$response['order_id'] = $order_id;
            $response['success'] = true;
            $response['event'] = $event;
            $response['message'] = $message;


            die(wp_json_encode($response));
        }
        if ($event == 'upgrade') {

            $domain_count = 5;

            if ($tier == 1) {
                $domain_count = 5;
            }
            if ($tier == 2) {
                $domain_count = 10;
            }
            if ($tier == 3) {
                $domain_count = 999;
            }

            $class_license_manager_manage_license = new class_license_manager_manage_license();

            //$LicenseManagerWoocommerce = new LicenseManagerWoocommerce();

            $args['prev_license_key'] = $prev_license_key;
            $args['license_key'] = $license_key;
            $args['domain_count'] = $domain_count;
            $update_license = $class_license_manager_manage_license->update_license($args);

            $message = 'License upgrade done';

            //$response['order_id'] = $order_id;
            $response['success'] = true;
            $response['event'] = $event;
            $response['message'] = $message;


            die(wp_json_encode($response));
        }
        if ($event == 'downgrade') {


            $domain_count = 5;

            if ($tier == 1) {
                $domain_count = 5;
            }
            if ($tier == 2) {
                $domain_count = 10;
            }
            if ($tier == 3) {
                $domain_count = 999;
            }

            $class_license_manager_manage_license = new class_license_manager_manage_license();

            //$LicenseManagerWoocommerce = new LicenseManagerWoocommerce();


            $args['license_key'] = $license_key;
            $args['prev_license_key'] = $prev_license_key;
            $args['domain_count'] = $domain_count;
            $update_license = $class_license_manager_manage_license->update_license($args);

            $message = 'License downgrade done';

            //$response['order_id'] = $order_id;
            $response['success'] = true;
            $response['event'] = $event;
            $response['message'] = $message;


            die(wp_json_encode($response));
        }
    }
}

$AppSumoRequestRest = new AppSumoRequestRest();
