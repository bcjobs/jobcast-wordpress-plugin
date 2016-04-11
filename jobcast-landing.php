<?php 
session_start(); 

/*This page posts to itself and uses a validation php script based on the button that was pressed; */
if(isset($_POST['submitRegister'])) {
	require_once 'validateRegister.php';
} else if(isset($_POST['submitLogin'])) {
    require_once 'validateLogin.php';
}

$currentURL = $_SERVER["PHP_SELF"] . "?page=jobcast-plugin%2Fjobcast-plugin.php";
?>
<div class="wrap">
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

            <?php if(isset($_SESSION['error']) && $_SESSION['isLoginPage'] == false) : ?>
                <br>
                <br>
                <div class="errContainer">
                    <div class="displayError">
                        <strong>Error</strong> - <span id="errMessege"><?php echo $_SESSION['error'] ?></span>
                    </div>
                </div>

            <?php endif; ?>
	
            <br>
            <br>
            
            <div class="gridContainer">
                <form action="<?php echo $currentURL;?>" method="post" class="form">

                    <div class="formField"> 
                        <label class="fontawesome-user" for="registerFirstName"><span class="hidden">FirstName</span></label>
                        <input id="registerFirstName" name="firstname" type="text" class="formInput" placeholder="first name" required>
                    </div>

                    <div class="formField">   
                        <label class="fontawesome-user" for="registerLastName"><span class="hidden">Lastname</span></label>
                        <input id="registerLastName" name="lastname" type="text" class="formInput" placeholder="last name" required>
                    </div>


                    <div class="formField">
                        <label class="fontawesome-briefcase" for="registerCompany"><span class="hidden">Company</span></label>
                        <input id="registerCompany" name="company" type="text" class="formInput" placeholder="company name" required>
                    </div>
    		
                    <div class="formField"> 
                        <label class="fontawesome-envelope-alt" for="registerEmail"><span class="hidden">Email</span></label>
                        <input id="registerEmail" name="email" type="text" class="formInput" placeholder="email" required>
                    </div>

                    <div class="formField">
                        <label class="fontawesome-lock" for="registerPassword"><span class="hidden">Password</span></label>
                        <input id="registerPassword" name="password" type="password" class="formInput" placeholder="password" required>
                    </div>
            

                    <div class="formField">
                        <input id="formSubmit" type="submit" value="Register Now" name="submitRegister">
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

            <?php if(isset($_SESSION['error']) && $_SESSION['isLoginPage'] == true) : ?>
                <div class="errContainer">
                    <div class="displayError">
                        <strong>Error</strong> - <span id="errMessege"><?php echo $_SESSION['error'] ?></span>
                    </div>
                </div>
            <?php
                endif;
                if(isset($_SESSION['error']))
                    unset($_SESSION['error']);
            ?>
            <br>
                                        
            <div class="gridContainer">
                <form action="<?php echo $currentURL; ?>" method="post" class="form">
    

                    <div class="formField"> 
                        <label class="fontawesome-envelope-alt" for="loginEmail"><span class="hidden">Email</span></label>
                        <input id="loginEmail" name="email" type="text" class="formInput" placeholder="email" required>
                    </div>

                    <div class="formField">
                        <label class="fontawesome-lock" for="loginPassword"><span class="hidden">Password</span></label>
                        <input id="loginPassword" name="password" type="password" class="formInput" placeholder="password" required>
                    </div>
            

                    <div class="formField">
                        <input id="formSubmit" type="submit" name="submitLogin" value="Sign in">
                    </div>

                </form>

                <p class="textCenter">Not a member? <a href="#" id="signUp" class="signIn">Sign up now</a> <span class="fontawesome-arrow-right"></span></p>
            </div>
        
        </div> <!-- End of show Register -->
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script>

  $(document).ready(function(){
    var flag = false;
    var newHeight = $(window).height();

    $('#pluginContainer').css('min-height', newHeight);

    <?php 
        if($_SESSION['isLoginPage'] == true)
            echo 'flag = true';
    ?>
  
  	/* Using Jquery to only display Register or Login at one time; */
    if(flag == true)
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
