<?php
session_start();
if(!isset($_SESSION['userapi'])) {
		$_SESSION['error'] = "Account not activated, Please login to continue.";
		refresh_page();
		exit();
}
//sending a sessionsMe request everytime this page is loaded
$sessionsme = getSessionsMe($_SESSION['userapi']);

//If the request came back empty that means something went wrong so we delete the
//userapikey from the DB and request user to relogin
if(empty($sessionsme)) {
		$_SESSION['error'] = "An error occured, please log in again!";
		update_option('userapikey', 'Invalid', '', 'yes');
		$_SESSION['isLoginPage'] == true;
		refresh_page();
		exit();
}

/* Parsing through our SessionsMe and getting all the information about the users companies; */
$companyInfo = array();
$firstCompany = $sessionsme['companies'][0]['name'];

$numOfCompanies = count($sessionsme['companies']);

for($i = 0; $i < $numOfCompanies; $i++) {
		$companyInfo[$sessionsme['companies'][$i]['id']]['code'] = $sessionsme['companies'][$i]['embedCode'];
		$companyInfo[$sessionsme['companies'][$i]['id']]['name'] = $sessionsme['companies'][$i]['name'];
}

$_SESSION['company'] = $companyInfo;
update_option('usercompany', $companyInfo, '', 'yes');

$redirectErrorUrl = $_SESSION['url'] . '/deactivate_plugin.php';


/* Helper function for sessionsMe Request; */
function getSessionsMe($userapi) {
	$headers = array(
			'Content-Type: application/json; charset=UTF-8',
			'Connection: keep-alive',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
			'User-Api-Key: '. $userapi
	);

	$ch = curl_init('https://app.jobcast.net/api/v2.0/sessions/me');

	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true ); //this returns the body;
	curl_setopt($ch, CURL_HTTP_VERSION_1_1, true);

	$result = curl_exec($ch);
	curl_close($ch);

	if(strlen($result) < 100)
			return array();

	return json_decode($result , true);
	}
?>
<div class="wrap">
		<div id="pluginContainer">
				<div class="header">
						<div class="head">
								<div id="logo"></div>
								<ul class="nav">
										<li></li>
								</ul>
						</div>
				</div>

				<div class="container">
						<h1 id="homepageHeader">Welcome to Jobcast Plugin!</h1>
						<section class="trail">

								<div class="left">
								<!--Add company box-->

										<div class="buttons">
												<h1>Manage Jobs</h1>
														<br>
														<!-- Only showing the dropdown if user has more than one company;-->
														<?php if($numOfCompanies > 1) : ?>
																<div class="select-style">
																		<select name="company" value="Company" id="dropdown">
																				<option value="<?php echo $sessionsme['companies'][0]['id']; ?>">Select Company</option>
																				<?php
																						foreach($companyInfo as $key => $value)
																								echo '<option value="'.$key.'">'.$value['name'].'</option>';
																				?>
																		</select>
																</div>
														<?php endif; ?>

														<br>
														<input type="submit" id="addJob"  value="Add a Job">
														<br>
														<input type="submit" id="manageJobs" value="Manage Jobs">
														<br>
														<input type="submit" id="branding" value="Branding">
										</div>

								</div><!-- End of left;-->

								<!--Short Code description-->
								<div id="right">
										<?php if($numOfCompanies > 1) : ?>
												<div class="rightText">
														<h1>Select your company</h1>
														<h2>We noticed you have more than one company linked to your account,
																Please use the dropdown menu on the left to display the corresponding shortcodes for your companies.
																Also utilize this dropdown menu to select on which company the buttons underneath the dropdown will act on.</h2>
												</div>

											<hr>
										<?php endif; ?>

										<div class="rightText">
												<h1>Display Jobs Now!</h1>
												<h2>Simply create a new page or edit your exsisting page and add the short-code below to
							that page to display the current job openings you have on Jobcast!
							<br>This shortcode will also provide a fully functional job listing that is intractable with the user!</h2>

												<div class="col display">
														<span id="embeded">[jobcast companyname="<?php echo $firstCompany; ?>"]</span>
														<br>
												</div>

												<br>
												<hr>
												<h2>Below is an example of how your page you want to display your jobs on should look like.</h2>
												<div id="demoIMG"></div>

										</div>
								</div> <!-- End of right; -->

						</section>
				</div>
		</div>
</div>

<!-- This form stays invisible to the user but it will send the post request whenever a buttom in 'manage jobs' is clicked; -->
<form action="https://app.jobcast.net/authentication/apikeylogin" id="formLogin" method="post" target="_blank" style="display: none;">
		<input type="hidden" name="userApiKey" value="<?php echo $_SESSION['userapi']; ?>">
		<input id="redirect" type="hidden" name="redirectSuccessUrl" value="https://app.jobcast.net/dashboard/">
		<input type="hidden" name="redirectErrorUrl" value="<?php echo $redirectErrorUrl; ?>">
		<button type="submit" style="display: none;">Login</button>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>

var newHeight = $(document).height();
$('#pluginContainer').css('min-height', newHeight);

$(document).ready(function(){

	$("#dropdown").change(function(){
			var x = $("#dropdown option:selected").text();
			if(x == "Select Company")
					$("#embeded").text("[jobcast companyname=<?php echo $firstCompany; ?>]");
			else
					$("#embeded").text("[jobcast companyname=\""+x+"\"]");
	});


/* Using Javascript to submit our invisble form so we can make the request and get the browser to redirect to the sucessURL*/
	$("#addJob").click(function() {
			var companyID       = $("#dropdown").val();
			if(companyID == null) {
					companyID = <?php echo $sessionsme['companies'][0]['id']; ?>;
			}
			var newSuccessUrl   = "https://app.jobcast.net/dashboard/companies/"+ companyID+ "/jobs/new";

			document.getElementById('redirect').value = newSuccessUrl;
			document.getElementById('formLogin').submit();
	});

	$("#manageJobs").click(function() {
			var companyID       = $("#dropdown").val();
			if(companyID == null) {
					companyID = <?php echo $sessionsme['companies'][0]['id']; ?>;
			}
			var newSuccessUrl   = "https://app.jobcast.net/dashboard/companies/"+ companyID+ "/jobs";

			document.getElementById('redirect').value = newSuccessUrl;
			document.getElementById('formLogin').submit();
	});

	$("#branding").click(function() {
			var companyID       = $("#dropdown").val();
			if(companyID == null) {
					companyID = <?php echo $sessionsme['companies'][0]['id']; ?>;
			}
			var newSuccessUrl   = "https://app.jobcast.net/dashboard/companies/"+ companyID+ "/branding/careersite";

			document.getElementById('redirect').value = newSuccessUrl;
			document.getElementById('formLogin').submit();
	});
});
</script>
