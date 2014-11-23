/**
 * Created by zwg2 on 11/18/14.
 */
/**
 * Created by zwg2 on 11/18/14.
 */
$(document).ready(function()
{
	$("#weatherForm").validate(
		{
			rules:
			{
				city:
				{
					pattern: /^[^";@#\$&\*]+$/
				}
			},

			messages:
			{
				city:
				{
					pattern: "Illegal characters detected",
					required: "Please enter a city"
				}
			},tttt

			submitHandler: function(form)
			{
				$(form).ajaxSubmit(
					{
						type: "GET",
						url: "php_api.php",
						success: function(ajaxOutput)
						{
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		});

	// bind the onChange event to the checkbox
	$("#useGps").change(function()
	{
		// only get location when the box is checked
		if(this.checked)
		{
			// always verify if the browser supports geolocation
			if(navigator.geolocation)
			{
				navigator.geolocation.getCurrentPosition(getPosition, errorCallback);
			}
			// not supported
			else
			{
				$("#outputArea").html("Geolocation not supported");
			}
		}
		else
		{
			// enable the city input
			$("#city").prop("disabled", false);
		}
	});
});

/**
 * callback function for a successful attempt at geolocation
 *
 * @param object containing geolocation data
 **/
function getPosition(position)
{
	// set the form values to the location data
	$("#latitude").val(position.coords.latitude);
	$("#longitude").val(position.coords.longitude);

	// disable the city input
	$("#city").val("");
	$("#city").prop("disabled", true);
}

/**
 * callback function for an unsuccessful attempt at geolocation
 *
 * @param error code describing what went wrong
 **/
function errorCallback(error)
{
	// setup repetitive variables
	var outputId  = "#outputArea";
	var errorCode = error.code;

	// go through the different error conditions
	if(errorCode === error.PERMISSION_DENIED)
	{
		$(outputId).html("User declined geolocation");
	}
	else if(errorCode === error.POSITION_UNAVAILABLE)
	{
		$(outputId).html("Geolocation unavailable");
	}
	else if(errorCode === error.TIMEOUT)
	{
		$(outputId).html("Geolocation request timed out");
	}
	/* else if(errorCode === error.UNKNOWN_ERROR)
	 {
	 $(outputId).html("An unknown error occured");
	 } */
}

/**
 * changes some text to a different color
 **/
function changeColor()
{
	document.getElementById("outputArea2").innerHTML = document.getElementById("outputArea2").innerHTML.fontcolor("red");
}



/**
 * highlights text
 **/
function highlightText()
{
	document.getElementById("outputArea3").style.backgroundColor="yellow";
	document.getElementById("outputArea3").style.display="inline-block";

	document.getElementById("outputArea4").style.backgroundColor = "red";
	document.getElementById("outputArea4").style.display = "inline-block";
}


/**
 * highlights a word within a line
 **/
function highlightWord() {

	var str = document.getElementById("outputArea5").innerHTML;
	//var res = str.search(/blue/g, "WITHIN");
	//res.style.backgroundColor = "blue";

	var result = str.replace(/blue/g, "<span style='background-color: blue;'> blue </span>");
	document.getElementById("outputArea5").innerHTML = result;


	//document.getElementById
	//document.getElementById("outputArea5").innerHTML=str.replace(/blue/g, res);

}



/**
 * finds all "and", replace with "BUT" and vic versa
 **/
function findReplace($input)
{
	var str = document.getElementById("outputArea4").innerHTML;
	var res = str.replace(/in/g, $input);
	document.getElementById("outputArea4").innerHTML = res;

	var str = document.getElementById("outputArea3").innerHTML;
	var res = str.replace(/in/g, $input);
	document.getElementById("outputArea3").innerHTML = res;


}

