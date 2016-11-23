<?php


/* Parsing through our jobcast_sessionsme and getting all the information about the users companies; */

$jobcast_companyInfo = array();
$jobcast_firstCompany = $jobcast->sessionsMe['companies'][0]['name'];

$jobcast_numOfCompanies = count($jobcast->sessionsMe['companies']);

foreach($jobcast->sessionsMe['companies'] as $value) {

		$jobcast_companyInfo[$value['id']]['code'] = $value['embedCode'];
		$jobcast_companyInfo[$value['id']]['name'] = $value['name'];
		
}

update_option('jobcast_usercompany', $jobcast_companyInfo, '', 'yes');

?>
<div id='jobcast_plugin' class="wrap">
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
														<?php if($jobcast_numOfCompanies > 0) : ?>
																<div class="select-style">
																		<select name="company" value="Company" id="dropdown">
																				<option value="<?php echo $jobcast->sessionsMe['companies'][0]['id']; ?>">Select Company</option>
																				<?php
																						foreach($jobcast_companyInfo as $key => $value)
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
										<?php if($jobcast_numOfCompanies > 1) : ?>
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
														<span id="embeded">[jobcast companyname="<?php echo $jobcast_firstCompany; ?>"]</span>
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

		<input type="hidden" name="userApiKey" value="<?php echo $jobcast->stored_api; ?>">
		<input id="redirect" type="hidden" name="redirectSuccessUrl" value="https://app.jobcast.net/dashboard/">
		<input type="hidden" name="redirectErrorUrl" value="<?php echo $jobcast_redirectErrorUrl; ?>">
		<button type="submit" style="display: none;">Login</button>
		
</form>

<script>

var jobcast_firstCompany = '<?php echo $jobcast_firstCompany; ?>';
var jobcast_companyId = <?php echo $jobcast->sessionsMe['companies'][0]['id']; ?>;

</script>
