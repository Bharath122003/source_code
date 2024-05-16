<div class="container pt-2">
<div class="row">
<!--- Dashboard Area Start --->
<div class="col-xl-12">
<?php 
global $wpdb;
$ecy_table = $wpdb->base_prefix . "ecywebsec"; 
$ecyQry = $wpdb->get_results("SELECT * FROM $ecy_table");
$ecy_access = array_column($ecyQry,'access_key');
$ecy_scData = array_column($ecyQry,'scan_data');
$ecy_DataID = array_column($ecyQry,'id');
if(isset($ecy_scData[0])) {
$ecy_Data = json_decode($ecy_scData[0]); }
if (!empty($ecy_access[0]) && !isset($_POST['updatekey'])) {
require plugin_dir_path(__FILE__) . 'description.php';
$description = json_decode($json_description);
?>
<!--- Nav Bar Start --->
<nav class="navbar navbar-light rounded ecy-bg">
	<div class="col-xl-12">
	<div class="row">
		<div class="col-5">
		<a class="btn-link shadow-none" href="https://ecylabs.com/" target="_blank">
		<img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/ecylogo_xs.png" class="img-fluid" alt="Website Security Platform" width="170px" height="120px"></a>
		</div>
		<div class="col-4">
		<h4 class="text-white mt-3"><?php echo parse_url(site_url(), PHP_URL_HOST); ?></h4>
		</div>
		<div class="col-3">
		<form method="post" action="">
		<div class="d-flex justify-content-center mt-2 float-right">
		<button role="button" class="btn btn-light btn-outline-success ml-2" data-toggle="tooltip" title="Update API key" id="update" name="updatekey"><i class="fa fa-key" aria-hidden="true"></i></button>
		<button role="button" class="btn btn-light btn-outline-success ml-2" data-toggle="tooltip" title="Sync" id="data_sync" name="data_sync"><i class="fa fa-refresh" aria-hidden="true"></i> Sync</button>
		</div>
		</form>
		</div>
	</div>
	</div>
</nav>
<!--- Nav Bar End --->
<!-- ================================= -->
<div class="row">
<!--- count cards start --->
	<div class="col-lg-6">
	<div class="row">
	<!--- Alert message --->
	<?php if (isset($ecy_msg)) { ?> 
	<div class="alert alert-primary col-11 mt-4 ml-4" role="alert"><?php echo esc_attr($ecy_msg); ?><a href="#" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a></div> 
	<?php } ?>
	<!--- Alert message --->
		<div class="col-6">
			<div class="card">
			<div class="d-inline-block">
			<img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/icon_assets.png" class="img-fluid" width="70px" height="100px">
			<h6 class="text-muted px-2 float-right mt-2 pl-2">Asset - <?php echo $ecy_Data->dashboard_details->asset_count; ?></h6>
			</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card">
			<div class="d-inline-block">
			<img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/icon_urls.png" class="img-fluid" width="70px" height="100px">
			<h6 class="text-muted float-right mt-2 pl-2"> URLs - <?php echo $ecy_Data->dashboard_details->url_count; ?></h6>
			</div>
			</div>
		</div>
			<div class="col-6">
			<div class="card">
			<div class="d-inline-block">
			<img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/sev_critical.png" class="img-fluid" width="70px" height="100px">
			<h6 class="text-muted float-right mt-2 pr-2">Critical - <?php echo $ecy_Data->sev_count->critical; ?></h6>
			</div>
			</div>
		</div>
			<div class="col-6">
			<div class="card">
			<div class="d-inline-block">
			<img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/sev_high.png" class="img-fluid" width="70px" height="100px">
			<h6 class="text-muted float-right mt-2 pr-2">High - <?php echo $ecy_Data->sev_count->high; ?></h6>
			</div>
			</div>
			</div>
		<div class="col-6">
			<div class="card">
			<div class="d-inline-block">
			<img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/sev_medium.png" class="img-fluid" width="70px" height="100px">
			<h6 class="text-muted float-right mt-2 pr-2">Medium - <?php echo $ecy_Data->sev_count->medium; ?></h6>
			</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card">
			<div class="d-inline-block">
			<img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/sev_low.png" class="img-fluid" width="70px" height="100px">
			<h6 class="text-muted float-right mt-2 pr-2">Low  - <?php echo $ecy_Data->sev_count->low; ?></h6>
			</div>
			</div>
		</div>
		<div class="col-12 text-center mt-2">
			<?php $secure_link = "http://dummy.ecylabs.website/security/center/login/?ltoken=" . ($ecy_access[0]); ?>
			<a href="<?php echo ($secure_link); ?>" target="_blank" class="btn ecy-bg text-white mt-2" role="button" title="View Detailed Report">View Detailed Report</a>
		</div>
	</div>
    </div>
<!--- category count chart --->
	<div class="col-lg-6">
	<div class="card">
	<h5 class="card-header border-0">Issue Category	<i class="fa fa-list-alt float-right" aria-hidden="true"data-toggle="modal" data-target="#catecountmodal"></i></h5>   
	<div class="card-body">
	<canvas id="chartjs_bar"></canvas>
	</div>
	</div>
	</div>
</div>
<!-- ================================= -->
<?php
/* Check List Class Property */
function class_properties($condition) {
if($condition == true) { 
$property = 'fa fa-check-circle text-success'; }
elseif($condition == false) { 
$property = 'fa fa-exclamation-circle text-warning'; }
return $property;
} ?>
<div class="row">
<!-- ================================= -->
<!--- Left Column Start --->
<div class="col-lg-8">
<p class="h4 mt-2">Basic Security Check</p>
<?php 
$basic_chkArr = (array) $ecy_Data->basic_check;
if(count($basic_chkArr) > 0) { ?>
<div id="accordion">
<!--- CMS Block Start --->
<?php 
if($ecy_Data->basic_check->cms == 'Not Found') {
$cms_prop = class_properties(true);
$cms_desc = $description->cms->cmsdes_true;
$cms_Data = '<strong class="text-dark">CMS not Detected</strong>'; }
else { 
$cms_prop = class_properties(false);
$cms_desc = $description->cms->cmsdes_false;
$cms_Data = '<strong class="text-dark">CMS Detected - '.$ecy_Data->basic_check->cms.'</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_cms" aria-expanded="true" aria-controls="collapse_cms"><i class="<?php echo $cms_prop; ?> mt-2 mx-3"></i>Disclosure of CMS<strong><?php echo '- '.$ecy_Data->basic_check->cms; ?></strong></button></h5>
	<div id="collapse_cms" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $cms_Data; ?>
	<p class="mt-3"><?php echo $cms_desc; ?></p>
	</div>
	</div>
	</div>
<!--- SPF Record Block Start --->
<?php 
if($ecy_Data->basic_check->spoofing) { 
$spoof = class_properties(false);
$spoof_desc = $description->spf->spfdes_false; }
else { 
$spoof = class_properties(true);
$spoof_desc = $description->spf->spfdes_true; }
if($ecy_Data->basic_check->spf_record) { 
$spf_Data = '<strong class="text-dark">SPF Record Found - '.$ecy_Data->basic_check->spf_record.'></strong>'; } 
else { 
$spf_Data = '<strong class="text-dark">No SPF Record Found</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_spf" aria-expanded="false" aria-controls="collapse_spf"><i class="<?php echo $spoof; ?> mt-2 mx-3"></i>Email Authentication - SPF</button></h5>
	<div id="collapse_spf" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $spf_Data; ?>
	<p class="mt-3"><?php echo $spoof_desc; ?><a target="_blank" href="https://support.google.com/a/answer/33786" class="badge badge-primary ml-2">Read More</a></p>
	</div>
	</div>
	</div>
<!--- DMARC Record Block Start --->
<?php
if($ecy_Data->basic_check->dmarc_record) { 
$dmarc = class_properties(true);
$dmarc_desc = $description->dmarc->dmarcdes_true;
$dmarc_Data = '<strong class="text-dark">DMARC Record Found - '.$ecy_Data->basic_check->dmarc_record.'</strong>'; }
else { 
$dmarc = class_properties(false);
$dmarc_desc = $description->dmarc->dmarcdes_false;
$dmarc_Data = '<strong class="text-dark">No DMARC Record Found</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_dmarc" aria-expanded="false" aria-controls="collapse_dmarc"><i class="<?php echo $dmarc; ?> mt-2 mx-3"></i>Email Authentication - DMARC</button></h5>
	<div id="collapse_dmarc" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $dmarc_Data; ?>
	<p class="mt-3"><?php echo $dmarc_desc; ?><a target="_blank" href="https://support.google.com/a/answer/2466580" class="badge badge-primary ml-2">Read More</a></p>
	</div>
	</div>
	</div>
<!--- Link Broken Block Start --->
<?php
if($ecy_Data->dashboard_details->broken_link > 0) {
$broken_desc = $description->blinks->blinksdes_false;
$Blink_Data = '<strong class="text-dark">Broken Links Found</strong>';
$bldisp = '<span class="badge badge-warning mt-2 mx-3">'.$ecy_Data->dashboard_details->broken_link.'</span>'; }
else {
$broken_desc = $description->blinks->blinksdes_true;	
$Blink_Data = '<strong class="text-dark">Broken links Not Found</strong>';
$bldisp = '<i class="'.class_properties(true).' mt-2 mx-3"></i>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_links" aria-expanded="false" aria-controls="collapse_links"><?php echo $bldisp; ?>Broken Links</button></h5>
	<div id="collapse_links" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $Blink_Data; ?>
	<p class="mt-3"><?php echo $broken_desc; ?><a target="_blank" class="badge badge-primary ml-2" href="https://ecylabs.com/kbarticles/link-checker-to-avoid-broken-link-hijacking/">Read More</a></p>
	</div>
	</div>
	</div>
<!--- High Availability Block Start --->
<?php 
if($ecy_Data->basic_check->lb_hosts) {
$lbhosts = class_properties(true); 
$lbhosts_desc = $description->lb->lbdes_true;
$lb_Data = '<strong class="text-dark">Load Balancer Detected - '.str_replace(',', ', ', $lb_hosts).'</strong>'; }
else { 
$lbhosts = class_properties(false);
$lbhosts_desc = $description->lb->lbdes_false;
$lb_Data = '<strong class="text-dark">Load Balancer not Detected</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_lb" aria-expanded="false" aria-controls="collapse_lb"><i class="<?php echo $lbhosts; ?> mt-2 mx-3"></i>Website High Availability Status</button></h5>
	<div id="collapse_lb" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $lb_Data; ?>
	<p class="mt-3"><?php echo $lbhosts_desc; ?><a target="_blank" href="https://www.digitalocean.com/community/tutorials/an-introduction-to-haproxy-and-load-balancing-concepts" class="badge badge-primary ml-2">Read More</a></p>
	</div>
	</div>
	</div>
<!--- Malware Block Start --->
<?php 
if($ecy_Data->basic_check->malware) { 
$malware_prop = class_properties(false);
$malware_desc = $description->malware->malwaredes_false;
$malware_Data = '<strong class="text-dark">Malicious URL Found</strong>'; }
else { 
$malware_prop = class_properties(true);
$malware_desc = $description->malware->malwaredes_true;
$malware_Data = '<strong class="text-dark">Malicious URL Not Found</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_malware" aria-expanded="false" aria-controls="collapse_malware"><i class="<?php echo $malware_prop; ?> mt-2 mx-3"></i>Malware URLs</button></h5>
	<div id="collapse_malware" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $malware_Data; ?>
	<p class="mt-3"><?php echo $malware_desc; ?></p>
	</div>
	</div>
	</div>
<!--- SSL/TLS Block Start --->
<?php 
if($ecy_Data->basic_check->ssl_issue) { 
$sslissue_prop = class_properties(false);
$sslissue_Data = '<strong class="text-dark">SSL/TLS Issue Found/strong>'; }
else { 
$sslissue_prop = class_properties(true);
$sslissue_Data = '<strong class="text-dark">SSL/TLS Issue Not Found</strong>'; }
$ssltls_desc = $description->ssltls->ssldes_true; ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_ssltls" aria-expanded="false" aria-controls="collapse_ssltls"><i class="<?php echo $sslissue_prop; ?> mt-2 mx-3"></i>SSL/TLS Issues</button></h5>
	<div id="collapse_ssltls" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $sslissue_Data; ?>
	<p class="mt-3"><?php echo $ssltls_desc; ?></p>
	</div>
	</div>
	</div>
<!--- URL Balcklist Block Start --->
<?php 
if($ecy_Data->basic_check->blacklist) { 
$blacklist = class_properties(false);
$bl_desc = $description->bl->bldes_false;
$blacklist_Data = '<strong class="text-dark">Domain Blacklisted</strong>'; }
else { 
$blacklist = class_properties(true);
$bl_desc = $description->bl->bldes_true;
$blacklist_Data = '<strong class="text-dark">Domain not Blacklisted</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_black" aria-expanded="false" aria-controls="collapse_black"><i class="<?php echo $blacklist; ?> mt-2 mx-3"></i>URL Blacklisted</button></h5>
	<div id="collapse_black" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $blacklist_Data; ?>
	<p class="mt-3"><?php echo $bl_desc; ?><a target="_blank" href="https://ecylabs.com/kbarticles/how-to-whitelist-website-that-blacklisted-due-to-malware-infection/" class="badge badge-primary ml-2">Read More</a></p>
	</div>
	</div>
	</div>
<!--- WAF Block Start --->
<?php 
if($ecy_Data->basic_check->waf == 'Not Found') { 
$waf_prop = class_properties(false);
$waf_desc = $description->waf->wafdes_false;
$blacklist_Data = '<strong class="text-dark">WAF not Found</strong>'; }
else { 
$waf_prop = class_properties(true);
$waf_desc = $description->waf->wafdes_true;
$blacklist_Data = '<strong class="text-dark">WAF Found</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_waf" aria-expanded="false" aria-controls="collapse_waf"><i class="<?php echo $waf_prop; ?> mt-2 mx-3"></i>Web Application Firewall</button></h5>
	<div id="collapse_waf" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $blacklist_Data; ?>
	<p class="mt-3"><?php echo $waf_desc; ?><a target="_blank" href="https://ecylabs.com/kbarticles/what-is-the-waf-a-web-application-firewall/" class="badge badge-pill badge-primary ml-2">Read More</a></p>
	</div>
	</div>
	</div>
<!--- SSL Status Block Start --->
<?php 
if($ecy_Data->basic_check->domain_ssl) { 
$ssl_domain_prop = class_properties(true);
$ssl_desc = $description->https_domain->httpsdes_true;
$ssl_domain_Data = '<strong class="text-dark">HTTPS Found</strong>'; }
else { 
$ssl_domain_prop = class_properties(false);
$ssl_desc = $description->https_domain->httpsdes_false;
$ssl_domain_Data = '<strong class="text-dark">HTTPS Not Found</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_ssl" aria-expanded="false" aria-controls="collapse_ssl"><i class="<?php echo $ssl_domain_prop; ?> mt-2 mx-3"></i>HTTPS Status</button></h5>
	<div id="collapse_ssl" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $ssl_domain_Data; ?>
	<p class="mt-3"><?php echo $ssl_desc; ?></p>
	</div>
	</div>
	</div>
<!--- Ports Open Block Start --->
<?php 
if (!empty($ecy_Data->basic_check->open_ports)) {
$open_port = explode(",", $ecy_Data->basic_check->open_ports);
$ports_count = count($open_port);
$open_port_Data	= '<strong class="text-dark">Open Ports Detected - '.str_replace(',', ', ', $ecy_Data->basic_check->open_ports).'</strong>'; }
else {
$open_port_Data	= '<strong class="text-dark">Open Ports not Detected</strong>'; }
$oport_desc = $description->ports_open->portdes_true; ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_port" aria-expanded="false" aria-controls="collapse_port"><span class="badge badge-primary mt-2 mx-3"><?php echo $ports_count; ?></span>Ports Open</button></h5>
	<div id="collapse_port" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $open_port_Data; ?>
	<p class="mt-3"><?php echo $oport_desc; ?></p>
	</div>
	</div>
	</div>
<!--- Name Server Block Start --->
<?php 
if (!empty($ecy_Data->basic_check->name_server)) {
$name_svr = explode(",", $ecy_Data->basic_check->name_server);
$ns_count = count($name_svr);
$ns_Data = '<strong class="text-dark">Name Server Detected - '.str_replace(',', ', ', $ecy_Data->basic_check->name_server).'</strong>'; }
if($ns_count > '1') {
$ns_prop = class_properties(true);
$ns_desc = $description->ns->nsdes_true; } 	
else { 
$ns_prop = class_properties(false);
$ns_desc = $description->ns->nsdes_false;
$ns_Data = '<strong class="text-dark">Name Server not Detected</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_ns" aria-expanded="false" aria-controls="collapse_ns"><i class="<?php echo $ns_prop; ?> mt-2 mx-3"></i>Name Server High Availability Status</button></h5>
	<div id="collapse_ns" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $ns_Data; ?>
	<p class="mt-3"><?php echo $ns_desc; ?></p>
	</div>
	</div>
	</div>
<!--- Website privacy policy Block Start --->
<?php
if($ecy_Data->dashboard_details->privacypage_exist < '1') {
$privacy_page = class_properties(false);
$privacy_desc = $description->privacydoc->privacydocdes_false;
$privacy_Data = '<strong class="text-dark">Privacy Policy Link Not Found</strong>'; }
else { 
$privacy_page = class_properties(true);
$privacy_desc = $description->privacydoc->privacydocdes_true;
$privacy_Data = '<strong class="text-dark">Privacy Policy Link Found but esnure the document adheres to privacy laws.</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_prpo" aria-expanded="false" aria-controls="collapse_prpo"><i class="<?php echo $privacy_page; ?> mt-2 mx-3"></i>Website privacy policy document</button></h5>
	<div id="collapse_prpo" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $privacy_Data; ?>
	<p class="mt-3"><?php echo $privacy_desc; ?></p>
	</div>
	</div>
	</div>
<!--- URL Categories Block Start --->
<?php 
if($ecy_Data->basic_check->domain_zone) {
$domain_zone = class_properties(true); }
else { 
$domain_zone = class_properties(false); }
$url_categories = ucwords(strtolower(str_replace(',', ', ', $ecy_Data->basic_check->url_category)));
if($url_categories) {
$url_categories_Data = '<strong class="text-dark">URL Categories Detected - '.$url_categories.'</strong>'; } 
else { 
$url_categories_Data = '<strong class="text-dark">URL Categories not Detected</strong>'; } 
$urlcate_desc = $description->url_cate->urlcatedes_true; ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_urlcat" aria-expanded="false" aria-controls="collapse_urlcat"><i class="<?php echo $domain_zone; ?> mt-2 mx-3"></i>URL Categories</button></h5>
	<div id="collapse_urlcat" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $url_categories_Data; ?>
	<p class="mt-3"><?php echo $urlcate_desc; ?><a target="_blank" href="https://www.google.com/search?q=URL+miscategorization+change+request" class="badge badge-primary ml-2">Read More</a></p>
	</div>
	</div>
	</div>
<!--- Registrar Block Start --->
<?php
if($ecy_Data->basic_check->registrar_status) {
$domain_change = class_properties(true);
$registrar_desc = $description->registrar->registrardes_true; }
else {
$domain_change = class_properties(false);
$registrar_desc = $description->registrar->registrardes_false; } 
if($ecy_Data->basic_check->domain_registrar) { 
$registrar_Data = '<strong class="text-dark">Registrar '.$ecy_Data->basic_check->domain_registrar.' locked '.$ecy_Data->basic_check->website.' to prevent unauthorized transfers</strong>'; } 
else { 
$registrar_Data = '<strong class="text-dark">Registrar safely lock status for your domain not detected</strong>'; } ?>
	<div class="card px-0 py-1 ecy-basic-width">
	<h5 class="mb-0"><button class="btn btn-link shadow-none" data-toggle="collapse" data-target="#collapse_registrar" aria-expanded="false" aria-controls="collapse_registrar"><i class="<?php echo $domain_change; ?> mt-2 mx-3"></i>Registrar Lock Status</button></h5>
	<div id="collapse_registrar" class="collapse" data-parent="#accordion">
	<div class="card-body">
	<?php echo $registrar_Data; ?>
	<p class="mt-3"><?php echo $registrar_desc; ?></p>
	</div>
	</div>
	</div>
</div>
<?php 
} 
else { ?>
<div class="lead">No data found, if you have started the sync then please allow sometime and latest data will be available shortly. Otherwise contact support@ecylabs.com</div>
<?php } ?>
</div>
<!-- ================================= -->
<!--- Right Column Start --->
<div class="col-lg-4">
<!--- website general details --->
	<div class="card px-0 py-0">
		<div class="card-body">
			<ul class="list-group list-group-flush">
				<li class="list-group-item d-flex justify-content-between align-items-center">IP:
				<span class="badge badge-primary"><?php echo $ecy_Data->basic_check->ip_addr; ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Hosted By:
				<span class="badge badge-primary"><?php echo $ecy_Data->banner_details->hosting_provider; ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Running On:
				<span class="badge badge-primary"><?php echo $ecy_Data->banner_details->app_version; ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Certificate Expiry:
				<span class="badge badge-primary"><?php echo $ecy_Data->banner_details->cert_exp; ?></span></li>
				<li class="list-group-item d-flex justify-content-between align-items-center">Domain Expiry:
				<span class="badge badge-primary"><?php echo $ecy_Data->banner_details->domain_exp; ?></span></li>
			</ul>
		</div>
	</div>
<!--- eCy Products cards start --->
	<div class="card">
		<div class="card-body">
		<h5 class="card-title">Continuous Security Monitoring (CSM)</h5>
		<p class="card-text">Let eCyLabs manage and protect your application from cyber threats with proven expertise. Outsourcing application security can be of substantial support for your business.. <a href="https://ecylabs.com/managed-appsec" target="_blank">read more</a></p>
		<div class="text-center">
		<a href="https://ecylabs.com/contact-us/" target="_blank" class="btn btn-primary btn-sm">Contact</a>
		</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
		<h5 class="card-title">Application Security Posture Management (ASPM)</h5>
		<p class="card-text">eCyLabs ASPM is an AI driven appcentric security platform to provide 360 degree view of your application security posture from code to cloud.. <a href="https://ecylabs.com/aspm/" target="_blank">read more</a></p>
		<div class="text-center">
		<a href="https://ecylabs.com/contact-us/" target="_blank" class="btn btn-primary btn-sm">Contact</a>
		</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
		<h5 class="card-title">Vulnerability Assessment & Penetration Testing (VAPT)</h5>
		<p class="card-text">eCyLabs automated penetration testing helps to test the risk of OWASP Top 10 Web Application Security Risks. Many security flaws in the OWASP Top 10 list can be identified with our automated tool.. <a href="https://ecylabs.com/blog/2021/03/09/penetration-testing-automation/" target="_blank">read more</a></p>
		<div class="text-center">
		<a href="https://ecylabs.com/contact-us/" target="_blank" class="btn btn-primary btn-sm">Contact</a>
		</div>
		</div>
	</div>
<!--- eCy Products cards End --->
</div>
<!-- ================================= -->
<!-- Category Modal -->
<div class="modal fade" id="catecountmodal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog mt-5 float-right" role="document">
		<div class="modal-content">
		<div class="modal-header py-2">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
		</div>
		<div class="modal-body">
		<?php 
		$ecybots = explode(',', str_replace('"',"", $ecy_Data->dashboard_details->chart_label));
		$ecyissue_count = explode(',', $ecy_Data->dashboard_details->chart_data);
		$cateArr = array_combine($ecybots, $ecyissue_count);
		?>
			<table class="table table-striped">
			<thead>
			<tr>
			<th scope="col">Scan Bots</th>
			<th scope="col">Issues</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($cateArr as $key => $value) { ?>
			<tr>
			<td scope="row"><?php echo $key; ?></td>
			<td scope="row"><?php echo $value; ?></th>
			</tr>
			<?php } ?>
			</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<!-- ================================= -->
</div>
</div>
<!--- Dashboard Area End --->
<?php }
elseif(isset($_POST['updatekey']) || empty($ecy_access[0])) {
require plugin_dir_path(__FILE__) . 'loginpage.php';
}
?>
</div>
</div>
