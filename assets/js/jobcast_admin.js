jQuery(function($){

  var companyID = null;

  var newHeight = $(document).height();
  $('#pluginContainer').css('min-height', newHeight);

	$("#dropdown").change(function(){
			var x = $("#dropdown option:selected").text();
			if(x == "Select Company")
					$("#embeded").text("[jobcast companyname=jobcast_firstCompany]");
			else
					$("#embeded").text("[jobcast companyname=\""+x+"\"]");
	});


  /* Using Javascript to submit our invisble form so we can make the request and get the browser to redirect to the sucessURL*/
  
	$("#addJob").click(function(e) {
	
      companyID = $("#dropdown").val() || jobcast_companyId;

			$('#redirect').val("https://app.jobcast.net/dashboard/companies/"+ companyID+ "/jobs/new");
			
			$('#formLogin').submit();
	});

	$("#manageJobs").click(function() {
	
			companyID = $("#dropdown").val() || jobcast_companyId;

			$('#redirect').val("https://app.jobcast.net/dashboard/companies/"+ companyID+ "/jobs");
			$('#formLogin').submit();
	});

	$("#branding").click(function() {
	
			companyID = $("#dropdown").val() || jobcast_companyId;

			$('#redirect').val("https://app.jobcast.net/dashboard/companies/"+ companyID+ "/branding/careersite");
			
			$('#formLogin').submit();
			
	});
	
});