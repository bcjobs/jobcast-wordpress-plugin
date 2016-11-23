<div id='jobcast_plugin' class="wrap">
	<div id="pluginContainer">
	  <!-- Header -->
		<div class="header">
			<div class="container head">
				<div id="logo"></div>
				<ul class="nav"><li></li></ul>
			</div>
		</div>
		<br>
		<br>

		<div id="showRegister">
			<div class="login-header" style="">
				<h1>30 Day Free Trial</h1>
			</div>

			<?php if(!empty($jobcast->errors)) : ?>
				<br>
				<br>
				<div class="errContainer">
					<div class="displayError">
            <?php foreach($jobcast->errors as $error) : ?>
						<strong>Error</strong> - <span id="errMessege"><?php echo $error ?></span>
						<?php endforeach; ?>
					</div>
				</div>

			<?php endif; ?>

			<br>
			<br>

			<div class="gridContainer">
				<form action="<?php echo $currentURL; ?>" method="post" class="form">

					<div class="formField"> 
						<label class="fontawesome-user" for="registerFirstName"><span class="hidden">FirstName</span></label>
						<input id="registerFirstName" name="jobcast_firstname" type="text" class="formInput" placeholder="first name" required>
					</div>

					<div class="formField">
						<label class="fontawesome-user" for="registerLastName"><span class="hidden">Lastname</span></label>
						<input id="registerLastName" name="jobcast_lastname" type="text" class="formInput" placeholder="last name" required>
					</div>


					<div class="formField">
						<label class="fontawesome-briefcase" for="registerCompany"><span class="hidden">Company</span></label>
						<input id="registerCompany" name="jobcast_company" type="text" class="formInput" placeholder="company name" required>
					</div>

					<div class="formField">
						<label class="fontawesome-envelope-alt" for="registerEmail"><span class="hidden">Email</span></label>
						<input id="registerEmail" name="jobcast_email" type="text" class="formInput" placeholder="email" required>
					</div>

					<div class="formField">
						<label class="fontawesome-lock" for="registerPassword"><span class="hidden">Password</span></label>
						<input id="registerPassword" name="jobcast_password" type="password" class="formInput" placeholder="password" required>
					</div>


					<div class="formField">
						<input id="formSubmit" type="submit" value="Register Now" name="jobcast_submitRegister">
					</div>

				</form>

				<p class="textCenter">Already a member? <a href="#" id="signIn" class="signIn">Sign in now</a> <span class="fontawesome-arrow-right"></span></p>

				<br>
				<br>
			</div>

		</div><!-- End of showRegister -->

		<div id="showLogin">
			<div class="login-header" style="padding-bottom: 50px;">
				<h1>Activate your account</h1>
			</div>

			<?php if(!empty($jobcast->errors)) : ?>
				<div class="errContainer">
					<div class="displayError">
						<?php foreach($jobcast->errors as $error) : ?>
						<strong>Error</strong> - <span id="errMessege"><?php echo $error ?></span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php
				endif;
			?>
			<br>

			<div class="gridContainer">
				<form action="<?php echo $currentURL; ?>" method="post" class="form">


					<div class="formField">
						<label class="fontawesome-envelope-alt" for="loginEmail"><span class="hidden">Email</span></label>
						<input id="loginEmail" name="jobcast_email" type="text" class="formInput" placeholder="email" required>
					</div>

					<div class="formField">
						<label class="fontawesome-lock" for="loginPassword"><span class="hidden">Password</span></label>
						<input id="loginPassword" name="jobcast_password" type="password" class="formInput" placeholder="password" required>
					</div>


					<div class="formField">
						<input id="formSubmit" type="submit" name="jobcast_submitLogin" value="Sign in">
					</div>

				</form>

				<p class="textCenter">Not a member? <a href="#" id="signUp" class="signIn">Sign up now</a> <span class="fontawesome-arrow-right"></span></p>
			</div>

		</div> <!-- End of show Register -->
	</div>
</div>

<script>

jQuery(function($){

	var flag = <?php echo isset($_POST['jobcast_submitLogin']) ? 'true' : 'false'; ?>;
	var newHeight = $(window).height();

	$('#pluginContainer').css('min-height', newHeight);

	  /* Using Jquery to only display Register or Login at one time; */
	if(flag === true)
		$("#showRegister").hide();
	else
		$("#showLogin").hide();

	$("#signIn").click(function(){
		$("#showRegister").hide();
		$("#showLogin").show();
	});

	$("#signUp").click(function(){
		$("#showRegister").show();
		$("#showLogin").hide();
	});

});

</script>
