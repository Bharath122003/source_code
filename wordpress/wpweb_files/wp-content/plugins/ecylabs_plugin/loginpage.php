<div class="container">
<div class="row justify-content-center mt-5">
<div class="col-md-6">
<!-- ================================= -->
<!-- Access form sarts -->
<form class="text-center border border-light p-5" method="post" action="">
<p class="h4 mb-4"><a class="btn-link shadow-none" href="https://ecylabs.com/" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/img/ecylogo_xs.png" class="img-fluid" alt="Website Security Platform" width="170px" height="120px"></a></p>
<div class="col-12 mt-1"><div class="alert alert-warning" role="alert">Update your API key to view the report</div></div>	
	<?php
	if (isset($ecy_cntmsg)) { ?>
	<div class="col-12 ml-4 mt-3 d-flex">
	<div class="alert alert-info" role="alert"><?php echo esc_attr($ecy_cntmsg); ?><a href="#" class="close ml-2 mb-2" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
	</div> 
	</div>
	<?php } ?>
	<textarea id="ecy_accesskey" name="ecy_accesskey" class="form-control mb-4" minlength="20"  required placeholder="API Secret Key"></textarea>
	<?php
	if (empty($ecy_access[0])) { ?>
	<button class="btn btn-primary btn-block my-4" name="continue2Dash">Submit</button>
	<p>If you already have an account <a target="_blank" href="https://ecylabs.tawk.help/article/getting-started-with-word-press-plugin">Get API key here</a></p>
	<p>Don't have an account? Please fill out the below form.</p>
	<?php } 
	if(isset($_POST['updatekey'])) { ?>
	<div class="row">
		<div class="col-sm">
		<button type="button" class="btn btn-secondary btn-block" onclick="window.location.reload();">Back</button>
		</div>
		<div class="col-sm">
		<input type="hidden" id="updtid" name="updtid" value="<?php echo esc_attr($ecy_DataID[0]); ?>">	
		<button class="btn btn-primary btn-block" name="updtkeybtn">Update Key</button>
		</div>
	</div>
	<?php } ?>
</form>
<!-- Access form sarts -->	
<?php
if (isset($_POST['loginpage'])) {  
    $firstname = sanitize_text_field($_POST['firstname']); 
    $lastname = sanitize_text_field($_POST['lastname']);
    $email = sanitize_email($_POST['email']);
    $companywebsite = sanitize_url($_POST['website']);    
    $targeturl = sanitize_url($_POST['targeturl']);
    $organization = sanitize_text_field($_POST['organization']);
    $apiendpoint = sanitize_text_field($_POST['apiendpoint']);
    $coderepourl = sanitize_text_field($_POST['coderepourl']); 
	$to = "sajeer.ecylabs@gmail.com";
    $admin_subject = "New User Registration From WP-Plugin"; 

    // Initialize admin message with mandatory fields
    $admin_message = nl2br("Firstname: $firstname\nLastname: $lastname\nEmail: $email\nOrganization: $organization\nCompany Website: $companywebsite\nTarget URL: $targeturl\n");

    // Add API Endpoint if not empty
    if ($_POST['coderepository'] == 'bitbucket') {
        $bitbucketAppPasscode = $_POST['bitbucketAppPasscode'];
        $bitbucketurl = $_POST['bitbucketurl'];
        
        // Include Bitbucket specific fields in the message
        $admin_message .= "Bitbucket App Passcode: $bitbucketAppPasscode<br>";
        $admin_message .= "Bitbucket URL: $bitbucketurl<br>";
    }

    // If Github is selected, include Github specific fields in the message
    if ($_POST['coderepository'] == 'github') {
        $githubAppPasscode = $_POST['githubAppPasscode'];
        $githubrepourl = $_POST['githubrepourl'];
        
        // Include Github specific fields in the message
        $admin_message .= "Github App Passcode: $githubAppPasscode<br>";
        $admin_message .= "Github RepoURL: $githubrepourl<br>";
    } 
$email_sent = wp_mail($to, $admin_subject, "", "New User Registered", $admin_message);

    if ($email_sent) {
        echo '<div class="col-md-12 py-4"><div class="d-flex justify-content-center w-90 mx-auto mt-3"><div class="alert alert-success" role="alert">API Key Request sent to the ecylabs. You will get the response shortly!</div></div></div>';	
    } else {
        
        echo '<div class="col-md-12 py-4"><div class="d-flex justify-content-center w-90 mx-auto mt-3"><div class="alert alert-danger" role="alert">Failed to send API Key Request. Please check your Email Server.</div></div></div>';	
    }
}	
?>	
	
<?php if (empty($ecy_access[0])) { ?>	
<form class="text-center border border-light p-5" id="login"   method="post" action="">
<div class="col-12 mt-1"><div class="alert alert-warning" role="alert">Registration Form</div></div>						
<div class="col-12 mt-2" align="left">
<label for="firstname"   class="col-form-label">First Name</label>
</div>
<div class=" col-lg-12">
<input name="firstname" id="firstname" type="text" required=""  class="form-control">
</div>
<div class="col-12 mt-2" align="left">
<label for="lastname"   class="col-form-label">Last Name</label>
</div>
<div class="col-lg-12">
<input name="lastname" id="lastname" type="text" required=""  placeholder="<?php $user_info = get_userdata(1);   echo '' . $user_info->last_name . "\n"; ?>" class="form-control">
</div>
<div class="col-12 mt-2" align="left">
<label for="email"  class="col-form-label"> Business Email</label>
</div>
<div class="col-lg-12">
<input name="email" id="email" type="email"  class="form-control"  required="" >
</div>
<div class="col-12 mt-2" align="left">
<label for="organization"   class="col-form-label">Organization Name</label>
</div>
<div class=" col-lg-12">
<input name="organization" id="organization" type="text" required=""  class="form-control">
</div>
<div class="col-12 mt-2" align="left">
<label for="website"   class="col-form-label">Company Website</label>
</div>
<div class=" col-lg-12">
<input name="website" id="website" type="text" required="" placeholder=""  class="form-control">
</div>	
<div class="col-12 mt-2" align="left">
<label for="targeturl"   class="col-form-label">Target URL</label>
</div>
<div class="col-lg-12">
<input name="targeturl" id="targeturl"  placeholder=" " value= "<?php echo site_url(); ?>" class="form-control" required>
</div>

<div class="col-12 mt-2" align="left">
<label for="apiendpoint"   class="col-form-label">Target API Endpoint (Optional)</label>
</div>
<div class=" col-lg-12">
<input name="apiendpoint" id="apiendpoint" type="text" class="form-control">
</div>

<!-- Code repository selection -->
<div class="col-12 mt-2" align="left">
<label for="coderepository" class="col-form-label">Repository Link for Code Scan (Optional)</label>
</div>
<div class="col-lg-12">
<select name="coderepository" id="coderepository" class="form-control">
<option value="none" selected disabled>Select Code Repository</option>
<option value="bitbucket">Bitbucket</option>
<option value="github">Github</option>
</select>
</div>

<!-- Additional fields for Bitbucket -->
<div id="bitbucketFields" style="display: none;">
<div class="col-12 mt-2" align="left">
<label for="bitbucketurl" class="col-form-label">Code Repository URL (optional):</label>
</div>
<div class="col-lg-12">
<input name="bitbucketurl" id="bitbucketurl" type="text" class="form-control">
</div>

<div class="col-12 mt-2" align="left">
<label for="bitbucketAppPasscode" class="col-form-label">Enter Repository Credentials (if private)</label>
</div>
<div class="col-lg-12">
<input name="bitbucketAppPasscode" id="bitbucketAppPasscode" type="text" class="form-control">
</div>
</div>

<!-- Additional fields for Github -->
<div id="githubFields" style="display: none;">
<div class="col-12 mt-2" align="left">
<label for="githubrepourl" class="col-form-label">Code Repository URL (optional):</label>
</div>
<div class="col-lg-12">
<input name="githubrepourl" id="githubrepourl" type="text" class="form-control">
</div>
<div class="col-12 mt-2" align="left">
<label for="githubAppPasscode" class="col-form-label">Enter Repository Credentials (if private)</label>
</div>
<div class="col-lg-12">
<input name="githubAppPasscode" id="githubAppPasscode" type="text" class="form-control">
</div>
</div>

<div class="col-12">
<button class="btn btn-primary my-4" name="loginpage">Generate API key</button>
</div>
</form>	
<?php } ?>	
</div>
</div>
</div>
<!-- ================================= -->

<script>
    // Show additional fields based on code repository selection
    document.getElementById('coderepository').addEventListener('change', function() {
        var selectedValue = this.value;
        if (selectedValue === 'bitbucket') {
            document.getElementById('bitbucketFields').style.display = 'block';
            document.getElementById('githubFields').style.display = 'none';
        } else if (selectedValue === 'github') {
            document.getElementById('bitbucketFields').style.display = 'none';
            document.getElementById('githubFields').style.display = 'block';
        } else {
            document.getElementById('bitbucketFields').style.display = 'none';
            document.getElementById('githubFields').style.display = 'none';
        }
    });
</script>

