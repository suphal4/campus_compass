<?php
//Function returns a boolean true if the language is safe (doesn't contain profanity)
	    function checkForProfanity($var)
	    {
	    	$valid = true;
	    	$array = explode(" ",$var);//splits the string into each individual word
	    	array_push($array, str_replace(' ','',$var));

	    	foreach($array as &$str)//Will loop and check each word submitted within the message
	    	{
		    	$url = "https://www.purgomalum.com/service/containsprofanity?text=".$str;//Links to the profanity checking url

				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		    	$resp = curl_exec($curl);//Executes the api request
				curl_close($curl);

				if ($resp == 'true')//The api returns the word true if there is profanity present
				{
					$valid = false;
				}
	    	}
	    	return $valid;

	    }

 ?>