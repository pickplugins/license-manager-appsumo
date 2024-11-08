<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins
*/

if (!defined('ABSPATH')) exit;  // if direct access















add_filter("appsumoValidateLicense", "appsumo_validate_license", 99, 2);
function appsumo_validate_license($return, $request)
{


  $requestPrams =  $request->get_params();

  error_log("appsumo_validate_license");


  if (empty($requestPrams)) return $return;

  $code = isset($requestPrams['code']) ? $requestPrams['code'] : '';
  $email = isset($requestPrams['email']) ? $requestPrams['email'] : '';
  $first_name = isset($requestPrams['first_name']) ? $requestPrams['first_name'] : '';



  if (empty($code)) return $return;

  $url = 'https://appsumo.com/openid/token/';
  $data = array(
    'client_id' => '006142572278',
    'client_secret' => 'd4ab0e1e71cc888bdfe3469dc6f173a9c7377a1b6e28313f6c2f1f4c',
    'code' => $code,
    'redirect_uri' => 'https://pickplugins.com/appsumo-register/',
    'grant_type' => 'authorization_code',
  );
  $headers = array('Content-type: application/json');

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $tokenresponse = curl_exec($ch);
  curl_close($ch);

  // var_dump($response);

  error_log($tokenresponse);
  $tokenresponse = json_decode($tokenresponse);

  $access_token = isset($tokenresponse->access_token) ? $tokenresponse->access_token : '';

  $url = 'https://appsumo.com/openid/license_key/?access_token=' . $access_token;

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

  $licenseresponse = curl_exec($curl);

  $licenseresponse = json_decode($licenseresponse);
  $license_key = isset($licenseresponse->license_key) ? $licenseresponse->license_key : '';
  $status = isset($licenseresponse->status) ? $licenseresponse->status : '';
  $tier = isset($licenseresponse->tier) ? $licenseresponse->tier : '';
  $user_fingerprint = isset($licenseresponse->user_fingerprint) ? $licenseresponse->user_fingerprint : '';
  error_log(serialize($licenseresponse));

  curl_close($curl);



  $order_id = 0;

  if ($status == 'Active') {

    $post_data = [];
    $post_data['email'] = $email;
    $post_data['first_name'] = $first_name;
    $post_data['license_key'] = $license_key;

    // Localhost data
    //$post_data['product_id'] = 130602;
    $order_id = appSumo_create_wc_order($post_data);
    $license_data = [];
    $user_id =  email_exists($email);

    $license_data['order_id'] = $order_id;
    $license_data['user_id'] = $user_id;
    $license_data['license_email'] = $email;
    $license_data['license_key'] = $license_key;

    $class_license_manager_manage_license = new class_license_manager_manage_license();
    $update_license = $class_license_manager_manage_license->update_license($license_data);

    error_log(serialize($update_license));

    return true;
  } else {
    return false;
  }
}




function appSumo_create_wc_order($post_data)
{


  $email      = isset($post_data['email']) ? $post_data['email'] : '';
  $first_name      = isset($post_data['first_name']) ? $post_data['first_name'] : '';
  $license_key      = isset($post_data['license_key']) ? $post_data['license_key'] : '';

  $license_data = get_license_data($license_key);

  error_log(serialize($license_data));

  $product_id     = isset($license_data['product_id']) ? (int)$license_data['product_id'] : '';
  $product_variation_id     = isset($license_data['variation_id']) ? (int)$license_data['variation_id'] : '';





  $response = new stdClass();

  if (empty($email)) {
    die(wp_json_encode($response));
  }


  $user_id =  email_exists($email);

  if (!$user_id && false == username_exists($email)) {
    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
    $user_id = wp_create_user($email, $random_password, $email);

    $user = get_user_by('ID', $user_id);
    $user->add_role('customer');


    $to = $email;
    $subject = 'PickPlugins -  User info';
    $body = '<div>
    <p>Hi, Welcome to PickPlugins.</p>
    <ul class="wp-block-list">
        <li>User Email: ' . $email . '</a></li>
        <li>Password: ' . $random_password . '</a></li>

        <li><a href="https://pickplugins.com/my-account/">My Account</a></li>
        <li><a href="https://pickplugins.com/create-support-ticket/">Create Support Ticket</a></li>
    </ul>

<p>Please try reset password to get access if needed.</p>
<a href="https://pickplugins.com/my-account/lost-password/">Reset Password</a>


<p><strong>PickPlugins</strong> – WordPress Free & Premium Plugins</p>
    <img decoding="async" src="https://pickplugins.com/wp-content/uploads/2024/07/pickplugins-ceo.png" alt="" style="width:150px">
    
</div>';

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);
  }










  $order = wc_create_order();


  if (!empty($product_variation_id)) {
    $product_variation = wc_get_product($product_variation_id);
    $downloads = $product_variation->get_downloads();
    $product_variation->set_downloads($downloads);
    $order->add_product($product_variation);
  } else {
    $product_variation = wc_get_product($product_id);
    $order->add_product($product_variation);
  }

  $order->calculate_totals();

  // $order->update_meta_data('checkoutTranscId', $checkoutTranscId);
  $order->update_meta_data('license_create', 'no');


  $address = array(
    'first_name' => $first_name,
    'last_name'  => '',
    'company'    => '',
    'email'      => $email,
    'phone'      => '',
    'address_1'  => '',
    'address_2'  => '',
    'city'       => '',
    'state'      => '',
    'postcode'   => '',
    'country'    => ''
  );
  $order->set_address($address, 'billing');
  $order->set_customer_id($user_id);

  $order->set_payment_method('appSumo');
  $order->set_payment_method_title('appSumo');
  $order->update_status('completed');

  $order->save();
  $order_id = $order->get_id();

  return $order_id;
}

//get_license_data("00000000-aaaa-1111-bbbb-abcdef012345");

function get_license_data($license_key)
{

  $response = [];

  $meta_query = [];



  $meta_query[] = array(
    'key' => 'license_key',
    'value' => $license_key,
    'compare' => '=',
  );




  $query_args = array(
    'post_type' => 'license',
    'post_status' => 'any',
    'meta_query' => $meta_query,
    'posts_per_page' => -1,
  );

  $wp_query = new WP_Query($query_args);

  if ($wp_query->have_posts()) :
    while ($wp_query->have_posts()) : $wp_query->the_post();
      $post_id = get_the_ID();

      $product_id = get_post_meta($post_id, 'product_id', true);
      $variation_id = get_post_meta($post_id, 'variation_id', true);


      $response['product_id'] = $product_id;
      $response['variation_id'] = $variation_id;

    endwhile;
  endif;

  return $response;
}
