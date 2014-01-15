
$(document).ready(function(){

	// hide #back-top first
	$("#back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});


function checkform(f)
{
	var done = 0;
	var e = document.getElementById('err');
	if(f.fname.value == "")
	{
		e.innerHTML = "Please enter your First Name !";
		done = 1; return false;
	}
	if(f.lname.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Last Name !";
		done = 1; return false;
	}
	if(f.password.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Password !";
		done = 1; return false;
	}
	if(f.houseno.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your House Number !";
		done = 1; return false;
	}
	if(f.street.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Street !";
		done = 1; return false;
	}
	if(f.cityname.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your City / town !";
		done = 1; return false;
	}
	if(f.postcode.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Postal code !";
		done = 1; return false;
	}
	if(f.country.value == 0 && done == 0)
	{
		e.innerHTML = "Please enter your Country !";
		done = 1; return false;
	}
	if(f.resadd.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your residence address !";
		done = 1; return false;
	}
	if(f.month.value == 0 && done == 0)
	{
		e.innerHTML = "Please enter your Birth month !";
		done = 1; return false;
	}
	if(f.date.value == 0 && done == 0)
	{
		e.innerHTML = "Please enter your Birth date !";
		done = 1; return false;
	}
	if(f.year.value == 0 && done == 0)
	{
		e.innerHTML = "Please enter your Birth year !";
		done = 1; return false;
	}
	if(f.nationality.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Nationality !";
		done = 1; return false;
	}
	if(f.nationality.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Nationality !";
		done = 1; return false;
	}
	if(f.mobile.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Mobile Number !";
		done = 1; return false;
	}
	if(f.emailid.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Email Id !";
		done = 1; return false;
	}
	else
	{
		if(!(f.emailid.value).match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/))
		{
			e.innerHTML = "Please enter a valid Email Id !";
			done = 1; return false;
		}
	}
}
function checkconform(f)
{
	var done = 0;
	var e = document.getElementById('err');
	if(f.name.value == "")
	{
		e.innerHTML = "Please enter your Name !";
		done = 1; return false;
	}
	if(f.emailid.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your Email Id !";
		done = 1; return false;
	}
	else
	{
		if(!(f.emailid.value).match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/))
		{
			e.innerHTML = "Please enter a valid Email Id !";
			done = 1; return false;
		}
	}
	if(f.conno.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your contact Number !";
		done = 1; return false;
	}
	if(f.msg.value == "" && done == 0)
	{
		e.innerHTML = "Please enter your message body !";
		done = 1; return false;
	}
}
function checknum()
{
	if ((event.keyCode < 48) || (event.keyCode > 57))
	return false;
}