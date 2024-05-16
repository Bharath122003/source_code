<?php
/**
 * @package eCyLabs Web Security
 * @version 1.0
 */
/*
Plugin Name: eCyLabs Web Security
Description: Threat Detection bots to proactively analyze security threats as an outsider - VAPT, WAF and Hack Recovery.
Author: eCylabs
Version: 1.0
Author URI: https://ecylabs.com/
*/
/*=================================*/
/*--- WP Admin Page Sidemenu ---*/
add_action('admin_menu', 'ecy_sidebar_menu');
function ecy_sidebar_menu(){
add_menu_page( 'eCylabs: Website Security Platform', 'eCy Inspector', 'manage_options', 'ecylabs', 'ecyResPage' );
}
/*=================================*/
/*--- CSS Includes ---*/
wp_enqueue_style( 'maincss', plugin_dir_url(__FILE__). 'assets/css/bootstrap.min.css' );
wp_enqueue_style( 'maincssmap', plugin_dir_url(__FILE__). 'assets/css/bootstrap.min.css.map' );
wp_enqueue_style( 'fontawesome', plugin_dir_url(__FILE__). 'assets/css/font-awesome.css' );
wp_enqueue_style( 'fontawesomemin', plugin_dir_url(__FILE__). 'assets/css/font-awesome.min.css' );
/*=================================*/
/*--- Activate Plugin ---*/
function ecy_activate() {
	flush_rewrite_rules();
	global $wpdb;
	$ecy_table = $wpdb->base_prefix . "ecywebsec";  
	$charset_collate = $wpdb->get_charset_collate();
	$createsqlQry = "CREATE TABLE IF NOT EXISTS $ecy_table( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, access_key VARCHAR(512) NOT NULL, scan_data blob NOT NULL, verified BOOLEAN NOT NULL, stime TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE (access_key) )DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
	require_once(ABSPATH . "wp-admin/includes/upgrade.php");
	dbDelta($createsqlQry);
}
register_activation_hook( __FILE__, 'ecy_activate' ); 
/*=================================*/
/*--- Deactivate Plugin ---*/
function ecy_deactivate() {
	flush_rewrite_rules();
	global $wpdb;
	$ecy_table = $wpdb->base_prefix . "ecywebsec"; 
	$dropsqlQry = "DROP TABLE IF EXISTS $ecy_table";  
	$wpdb->query($dropsqlQry);
	delete_option("my_plugin_db_version"); 
} 
register_deactivation_hook( __FILE__, 'ecy_deactivate' );
/*=================================*/
/*--- Connect with the SC ---*/
function ecy_scconnect($ecykey) {
	$scurl = 'https://dummy.ecylabs.website/security/center/api-json-data/';
	$accessArgs = array(
    'headers'    => array(
        'Authorization' => esc_textarea( esc_attr( $ecykey ) ),
    ),
    'sslverify' => false, // Disable SSL verification
);
	$result = wp_remote_get( esc_url_raw( $scurl ), $accessArgs );
	if ( is_array( $result ) && ! is_wp_error( $result ) ) {
	$res_content = $result['body'];
	return $res_content; }
}
/*=================================*/
/*--- Validate Site URL ---*/
function ecy_validateurl($ecyRes) {
	$app_Target = explode("#",base64_decode($ecyRes->cust_access));
	$parse_Target = parse_url($app_Target[2]);
	$site_URL = parse_url(site_url());
	if($parse_Target['host'] == $site_URL['host']) {
	return true; }
	else {
	return false; }	
}
/*=================================*/
/*--- API call to eCySC ---*/
function ecyResPage() {
global $wpdb;
if (isset($_POST['continue2Dash'])) { 
$apikey = sanitize_text_field($_POST['ecy_accesskey']); 
$result = ecy_scconnect($apikey);
$json2Arr = json_decode($result);
if(ecy_validateurl($json2Arr)) {
if(isset($json2Arr->success)) {
$response = $json2Arr->success; 
if($response == true) {
$ecy_table = $wpdb->base_prefix . "ecywebsec"; 
$insertQry = $wpdb->insert($ecy_table, array( 'access_key' => $apikey, 'scan_data' => $result, 'verified' => $response));
if($insertQry == true) { 
$ecy_msg = 'Updated successfully'; }
}
else {
$ecy_cntmsg = $json2Arr->message; }
}
else {
$ecy_cntmsg = 'Something went wrong, Please contact support@ecylabs.com'; }
}
else {
$ecy_cntmsg = 'Api key is not matching with site URL'; }
}
/*=================================*/
/*--- Update sync ---*/
if (isset($_POST['data_sync'])) {
$ecy_table = $wpdb->base_prefix . "ecywebsec"; 
$selectQry = $wpdb->get_results("SELECT access_key FROM $ecy_table");
$ecyAccess = array_column($selectQry, 'access_key'); 
$result = ecy_scconnect($ecyAccess[0]);
$json2Arr = json_decode($result);
if(isset($json2Arr->success)) {
$response = $json2Arr->success;
if($response == true) {
$scdata = array( 'scan_data' => $result );	
$updtCond = array( 'access_key' => $ecyAccess[0] );
$updtQry = $wpdb->update($ecy_table,$scdata,$updtCond);     
if($updtQry == true) {
$ecy_msg = 'Data Updated Successfully'; }  
else {
$ecy_msg = 'No Update to Sync'; }
}
else {
$ecy_msg = 'Data Sync Failed'; }
}
else {
$ecy_msg = 'Something went wrong, Please contact support@ecylabs.com'; }
}
/*=================================*/
if (isset($_POST['updtkeybtn'])) {
$apikey = sanitize_text_field($_POST['ecy_accesskey']);
$updtID = sanitize_text_field($_POST['updtid']);
$result = ecy_scconnect($apikey);
$json2Arr = json_decode($result);
if(ecy_validateurl($json2Arr)) {
if(isset($json2Arr->success)) {
$response = $json2Arr->success; 
if($response == true) {
$ecy_table = $wpdb->base_prefix . "ecywebsec"; 
$updtCond = array('id' => $updtID);
$updateQry = $wpdb->update($ecy_table,array( 'access_key' => $apikey ),$updtCond);
if($updateQry == true) {
$ecy_msg = 'API Key Updated Successfully., click sync button to see the changes'; }
else {
$ecy_msg = 'No Changes Applied'; }
}
else {
$ecy_msg = $json2Arr->message; }
}
else {
$ecy_msg = 'Something went wrong, Please contact support@ecylabs.com'; }
}
else {
$ecy_msg = 'Api key is not matching with site URL'; }
}
/*=================================*/
/*--- eCy Dasboard ---*/
require plugin_dir_path(__FILE__) . 'dashboard.php';
/*=================================*/
/*--- JS Includes ---*/
require  plugin_dir_path(__FILE__) . 'assets/scripts/js/chartjs.php';
//wp_enqueue_script( 'jqueryJS', plugin_dir_url(__FILE__). 'assets/scripts/js/jquery.min.js', );
wp_enqueue_script( 'bootstrapJS', plugin_dir_url(__FILE__). 'assets/scripts/js/bootstrap.min.js', );
wp_enqueue_script( 'bootstrapbundleJS', plugin_dir_url(__FILE__). 'assets/scripts/js/bootstrap.bundle.min.js', );
wp_enqueue_script( 'chartbundleJS', plugin_dir_url(__FILE__). 'assets/scripts/js/Chart.bundle.js', );
}  
?>
