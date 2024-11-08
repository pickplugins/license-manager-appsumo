<?php
/*
Plugin Name: License manager - AppSumo
Plugin URI: http://pickplugins.com
Description: Job Alerts
Version: 1.0.4
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;  // if direct access 


class LicenseManagerAppSumo
{

	public function __construct()
	{
		//define('job_bm_job_alerts_plugin_url', plugins_url('/', __FILE__));
		//define('job_bm_job_alerts_plugin_url', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
		//define('job_bm_job_alerts_plugin_dir', plugin_dir_path(__FILE__));
		//define('job_bm_job_alerts_textdomain', 'job-board-manager-job-alerts');

		//define('JOBALERTS_TABLE_NAME', $wpdb->prefix . 'job_bm_job_alerts');



		// Class
		require_once(plugin_dir_path(__FILE__) . 'includes/classes/request-appsumo.php');
		//require_once(plugin_dir_path(__FILE__) . 'includes/functions-rest.php');
		require_once(plugin_dir_path(__FILE__) . 'includes/functions.php');
		//require_once( plugin_dir_path( __FILE__ ) . 'includes/class-post-meta.php');	
		// require_once(plugin_dir_path(__FILE__) . 'includes/class-shortcodes.php');
		// require_once(plugin_dir_path(__FILE__) . 'includes/class-functions.php');
		//require_once( plugin_dir_path( __FILE__ ) . 'includes/class-settings.php');		


		//	require_once(plugin_dir_path(__FILE__) . 'includes/functions.php');

		//require_once( plugin_dir_path( __FILE__ ) . 'templates/keyword-single-template-functions.php');	
		//require_once(plugin_dir_path(__FILE__) . 'includes/pickform/class-pickform.php');



	}
}

new LicenseManagerAppSumo();
