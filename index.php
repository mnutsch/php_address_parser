<?php

header("Content-type: text/xml");

require_once('city_array.php');
require_once('state_array.php');
require_once('country_array.php');

function strrpos_last ($string, $searchFor, $startFrom = 0) 
{ 
    $addLen = strlen ($searchFor); 
    $endPos = $startFrom - $addLen; 

    while (true) 
    { 
        if (($newPos = strpos ($string, $searchFor, $endPos + $addLen)) === false) break; 
        $endPos = $newPos; 
    } 

    return ($endPos >= 0) ? $endPos : false; 
} 

function check_if_country ($string)
{
  global $country_array;
	$output_country = "FALSE";
	
	if(in_array(trim($string), $country_array))
	{
		$output_country = $string;
	}
	else 
	{
		$output_country = "FALSE";
	}
	
	return $output_country;
}

function check_if_zip ($string)
{
	//return FALSE if not a zip code, return the zip code if a zip
	$output_zip = "FALSE";
	
	$check_zip_code = substr($string, -5);
	//echo "The check zip code is: " . $check_zip_code  . "<br>";
	if(substr($check_zip_code, 0 , 1)=="-")
	{
		//echo "The check zip code is not the zip code<br>";
	}
	else
	{
		if (is_numeric($check_zip_code)) 
		{
			//echo "'{$check_zip_code}' is numeric", PHP_EOL;
			//echo "The check zip code IS the zip code<br>";
			$output_zip = $check_zip_code;
	  	} 
	  	else 
  		{
  			//echo "'{$check_zip_code}' is NOT numeric", PHP_EOL;
  			//echo "The check zip code is NOT the zip code<br>";
  			
  		}
		//echo "<br>";
	}	
	//echo "<br>";

	if(strlen($check_zip_code < 6))
	{
		//look at right 10 digits to see if it is a zip code + 4
		//if so, then return the left 5 of those characters
		$check_zip_code = substr($string, -10);
		//echo "The check zip code is: " . $check_zip_code  . "<br>";
		$check_zip_code = substr($check_zip_code, 0, 5);
		//echo "The check zip code is: " . $check_zip_code  . "<br>";
		if (is_numeric($check_zip_code)) 
		{	
			//echo "'{$check_zip_code}' is numeric", PHP_EOL;
			//echo "<br>";
			//echo "The check zip code IS the zip code<br>";
			$output_zip = $check_zip_code;
		} 
		else 
		{
			//echo "'{$check_zip_code}' is NOT numeric", PHP_EOL;
			//echo "<br>";
		   //echo "The check zip code is NOT the zip code<br>";
		}
		//echo "<br>";
	}
	
	return $output_zip;
	
}

function check_if_state ($string)
{

	global $state_array;
	$output_state = "FALSE";
	
	if(in_array(trim($string), $state_array))
	{
		$output_state = $string;
	}
	else 
	{
		$output_state = "FALSE";
	}
	
	return $output_state;


}

function check_if_city ($string)
{

	global $city_array;
	$output_city = "FALSE";
	
	if(in_array(trim($string), $city_array))
	{
		$output_city = $string;
	}
	else 
	{
		$output_city = "FALSE";
	}
	
	return $output_city;

}

if(addslashes($_GET['address']) != '')
{

$input = "";

$input = addslashes($_GET['address']);

//declare variables
$lowercase_input = "";
$pos = 0;
$pos2 = 0;
$new_pos = 0;
$first_section = "";
$second_section = "";
$third_section = "";
$fourth_section = "";
$last_section = "";
$found_in_current_section = 0;
$extracted_country = "";
$extracted_zip = "";
$extracted_state = "";
$extracted_city = "";
$extracted_street = "";

//declare arrays

//get input from form
//$input = "701 East 34th Street, Tacoma, WA 98404-1234 United States of America";
//echo "The Input is: " . $input . "<br>";
//echo "<br>";

//convert all the text to lowercase
$lowercase_input = strtolower($input);
//echo "The lowercase Input is: " . $lowercase_input . "<br>";
//echo "<br>";

$pos = strlen($lowercase_input);

//1 of 5 

$found_in_current_section = 0;
$mystring = "";

while ($found_in_current_section == 0)
{
	//echo "Working on first section of the address<br>";
	$lowercase_input = substr($lowercase_input, 0, $pos);
	//echo "The remaining lowercase Input is now: " . $lowercase_input . "<br>";
	$mystring = $lowercase_input;
	$findme   = ' ';	
	$pos = strrpos_last($mystring, $findme);
	if ($pos === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
	    //echo "The string '$findme' was found in the string '$mystring'";
   	 //echo " and exists at position $pos";
	}
	//echo "<br>";
	$findme   = ',';
	$pos2 = strrpos_last($mystring, $findme);
	if ($pos2 === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
    	//echo "The string '$findme' was found in the string '$mystring'";
    	//echo " and exists at position $pos2";
	}
	//echo "<br>";
	if ($pos2 == ($pos - 1))
	{
  		$new_pos = $pos;
	}
	else if (($pos === false) && ($pos2 === false)) //if there isn't a comma or a space
	{
 	 //INSERT SOMETHING HERE
 	 $new_pos = strlen($lowercase_input);
	}
	else if($pos > $pos2)
	{
 	 	$new_pos = $pos;
	}
	else
	{
 	 	$new_pos = $pos2;
	}
	//echo "The new pos is: " . $new_pos  . "<br>";

	$first_section = substr($lowercase_input, $new_pos) . $first_section;
	//echo "The first section is: " . $first_section  . "<br>";
	
	if (check_if_zip ($first_section) == "FALSE")
	{
		//echo "This is not a zip code<br>";
	}
	else
	{
		//echo "This is a zip code. The five digit zip code is " . check_if_zip ($first_section) . "<br>";
		if ($extracted_zip == "")
		{
			$extracted_zip = check_if_zip ($first_section);
		}
		$found_in_current_section = 1;
	}

	if (check_if_country($first_section) == "FALSE")
	{
		//echo "This is not a country<br>";
	}
	else
	{
		//echo "This is a country. The country name is " . check_if_country($first_section) . "<br>";
		if ($extracted_country == "")
		{
			$extracted_country = $first_section;
		}
		$found_in_current_section = 1;
	}
	
	if (check_if_state($first_section) == "FALSE")
	{
		//echo "This is not a state<br>";
	}
	else
	{
		//echo "This is a state. The state name is " . check_if_state($first_section) . "<br>";
		if ($extracted_state == "")
		{
			$extracted_state = $first_section;
		}
		$found_in_current_section = 1;
	}
	
	if (check_if_city($first_section) == "FALSE")
	{
		//echo "This is not a city<br>";
	}
	else
	{
		//echo "This is a city. The city name is " . check_if_city($first_section) . "<br>";
		if ($extracted_city == "")
		{
			$extracted_city = $first_section;
		}
		$found_in_current_section = 1;
	}
	
	if ($pos2 == ($pos - 1))
	{
		//echo "The next character is a comma. Starting a new section.";
  		$found_in_current_section = 1;
	}
	
	//echo "looking at the character: " . substr($lowercase_input, ($new_pos - 1), 1) . "<br>";
	if (is_numeric(substr($lowercase_input, ($new_pos - 1), 1)))
	{
		//echo "That character is numeric. Starting a new section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		//echo "That character is not numeric.<br>";
	}
	
	if ($lowercase_input == "")
	{
		//echo "There is no text left. Skipping this section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		//echo "There is still text.<br>";
	}
	
	//echo "<br>";
}

//2 of 5

$found_in_current_section = 0;
$mystring = "";

if ($pos2 == ($pos - 1))
{
  $new_pos = $pos2;
  $lowercase_input = substr($lowercase_input, 0, ($pos - 1));
}
else
{
	$lowercase_input = substr($lowercase_input, 0, $pos);
}

while ($found_in_current_section == 0)
{
	//echo "Working on second section of the address<br>";
	$lowercase_input = substr($lowercase_input, 0, $pos);
	//echo "The remaining lowercase Input is now: " . $lowercase_input . "<br>";
	$mystring = $lowercase_input;
	$findme   = ' ';	
	$pos = strrpos_last($mystring, $findme);
	if ($pos === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
	    //echo "The string '$findme' was found in the string '$mystring'";
   	 //echo " and exists at position $pos";
	}
	//echo "<br>";
	$findme   = ',';
	$pos2 = strrpos_last($mystring, $findme);
	if ($pos2 === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
    	//echo "The string '$findme' was found in the string '$mystring'";
    	//echo " and exists at position $pos2";
	}
	//echo "<br>";
	if ($pos2 == ($pos - 1))
	{
  		$new_pos = $pos;
	}
	else if (($pos === false) && ($pos2 === false)) //if there isn't a comma or a space
	{
 	 //INSERT SOMETHING HERE
 	 $new_pos = strlen($lowercase_input);
	}
	else if($pos > $pos2)
	{
 	 	$new_pos = $pos;
	}
	else
	{
 	 	$new_pos = $pos2;
	}
	//echo "The new pos is: " . $new_pos  . "<br>";

	$second_section = substr($lowercase_input, $new_pos) . $second_section;
	//echo "The second section is: " . $second_section  . "<br>";
	
	if (check_if_zip ($second_section) == "FALSE")
	{
		//echo "This is not a zip code<br>";
	}
	else
	{
		//echo "This is a zip code. The five digit zip code is " . check_if_zip ($second_section) . "<br>";
		$found_in_current_section = 1;
		if ($extracted_zip == "")
		{
			$extracted_zip = check_if_zip ($second_section);
		}
	}
	
	if (check_if_country($second_section) == "FALSE")
	{
		//echo "This is not a country<br>";
	}
	else
	{
		//echo "This is a country. The country name is " . check_if_country($second_section) . "<br>";
		if ($extracted_country == "")
		{
			$extracted_country = $second_section;
		}
		$found_in_current_section = 1;
	}
	
	if (check_if_state($second_section) == "FALSE")
	{
		//echo "This is not a state<br>";
	}
	else
	{
		//echo "This is a state. The state name is " . check_if_state($second_section) . "<br>";
		if ($extracted_state == "")
		{
			$extracted_state = $second_section;
		}
		$found_in_current_section = 1;
	}
	
	if (check_if_city($second_section) == "FALSE")
	{
		//echo "This is not a city<br>";
	}
	else
	{
		//echo "This is a city. The city name is " . check_if_city($second_section) . "<br>";
		if ($extracted_city == "")
		{
			$extracted_city = $second_section;
		}
		$found_in_current_section = 1;
	}
	
	if ($pos2 == ($pos - 1))
	{
		//echo "The next character is a comma. Starting a new section.";
  		$found_in_current_section = 1;
	}
	
	//echo "looking at the character: " . substr($lowercase_input, ($new_pos - 1), 1) . "<br>";
	if (is_numeric(substr($lowercase_input, ($new_pos - 1), 1)))
	{
		//echo "That character is numeric. Starting a new section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		//echo "That character is not numeric.<br>";
	}
	
		
	if ($lowercase_input == "")
	{
		//echo "There is no text left. Skipping this section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		//echo "There is still text.<br>";
	}
	
	//echo "<br>";
}

//3 of 5

$found_in_current_section = 0;
$mystring = "";

if ($pos2 == ($pos - 1))
{
  $new_pos = $pos2;
  $lowercase_input = substr($lowercase_input, 0, ($pos - 1));
}
else
{
	$lowercase_input = substr($lowercase_input, 0, $pos);
}

while ($found_in_current_section == 0)
{
	//echo "Working on third section of the address<br>";
	$lowercase_input = substr($lowercase_input, 0, $pos);
	//echo "The remaining lowercase Input is now: " . $lowercase_input . "<br>";
	$mystring = $lowercase_input;
	$findme   = ' ';	
	$pos = strrpos_last($mystring, $findme);
	
	if ($pos === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
	    //echo "The string '$findme' was found in the string '$mystring'";
   	 //echo " and exists at position $pos";
	}
	//echo "<br>";
	$findme   = ',';
	$pos2 = strrpos_last($mystring, $findme);
	if ($pos2 === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
    	//echo "The string '$findme' was found in the string '$mystring'";
    	//echo " and exists at position $pos2";
	}
	//echo "<br>";
	if ($pos2 == ($pos - 1))
	{
  		$new_pos = $pos;
	}
	else if (($pos === false) && ($pos2 === false)) //if there isn't a comma or a space
	{
 	 //INSERT SOMETHING HERE
 	 $new_pos = strlen($lowercase_input);
	}
	else if($pos > $pos2)
	{
 	 	$new_pos = $pos;
	}
	else
	{
 	 	$new_pos = $pos2;
	}
	//echo "The new pos is: " . $new_pos  . "<br>";

	$third_section = substr($lowercase_input, $new_pos) . $third_section;
	//echo "The third section is: " . $third_section  . "<br>";
	
	if (check_if_zip ($third_section) == "FALSE")
	{
		//echo "This is not a zip code<br>";
	}
	else
	{
		//echo "This is a zip code. The five digit zip code is " . check_if_zip ($third_section) . "<br>";
		if ($extracted_zip == "")
		{
			$extracted_zip = check_if_zip ($third_section);
		}
		$found_in_current_section = 1;

	}
	
	if (check_if_country($third_section) == "FALSE")
	{
		//echo "This is not a country<br>";
	}
	else
	{
		//echo "This is a country. The country name is " . check_if_country($third_section) . "<br>";
		if ($extracted_country == "")
		{
			$extracted_country = $third_section;
		}
		$found_in_current_section = 1;
	}
	
	if (check_if_state($third_section) == "FALSE")
	{
		//echo "This is not a state<br>";
	}
	else
	{
		//echo "This is a state. The state name is " . check_if_state($third_section) . "<br>";
		if ($extracted_state == "")
		{
			$extracted_state = $third_section;
		}
		$found_in_current_section = 1;
	}
	
	if (check_if_city($third_section) == "FALSE")
	{
		//echo "This is not a city<br>";
	}
	else
	{
		//echo "This is a city. The city name is " . check_if_city($third_section) . "<br>";
		if ($extracted_city == "")
		{
			$extracted_city = $third_section;
		}
		$found_in_current_section = 1;
	}
	
	if ($pos2 == ($pos - 1))
	{
		//echo "The next character is a comma. Starting a new section.";
  		$found_in_current_section = 1;
	}
	
	//echo "looking at the character: " . substr($lowercase_input, ($new_pos - 1), 1) . "<br>";
	if (is_numeric(substr($lowercase_input, ($new_pos - 1), 1)))
	{
		//echo "That character is numeric. Starting a new section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		//echo "That character is not numeric.<br>";
	}
	
	if ($lowercase_input == "")
	{
		//echo "There is no text left. Skipping this section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		//echo "There is still text.<br>";
	}
	
	//echo "<br>";
}


//4 of 5

$found_in_current_section = 0;
$mystring = "";

if ($pos2 == ($pos - 1))
{
  $new_pos = $pos2;
  $lowercase_input = substr($lowercase_input, 0, ($pos - 1));
}
else
{
	$lowercase_input = substr($lowercase_input, 0, $pos);
}

while ($found_in_current_section == 0)
{
	//echo "Working on fourth section of the address<br>";
	$lowercase_input = substr($lowercase_input, 0, $pos);
	//echo "The remaining lowercase Input is now: " . $lowercase_input . "<br>";
	$mystring = $lowercase_input;
	$findme   = ' ';	
	$pos = strrpos_last($mystring, $findme);
	if ($pos === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
	    //echo "The string '$findme' was found in the string '$mystring'";
   	 //echo " and exists at position $pos";
	}
	//echo "<br>";
	$findme   = ',';
	$pos2 = strrpos_last($mystring, $findme);
	if ($pos2 === false) 
	{
	    //echo "The string '$findme' was not found in the string '$mystring'";
	} 
	else 
	{
    	//echo "The string '$findme' was found in the string '$mystring'";
    	//echo " and exists at position $pos2";
	}
	//echo "<br>";
	if ($pos2 == ($pos - 1))
	{
  		$new_pos = $pos;
	}
	else if (($pos === false) && ($pos2 === false)) //if there isn't a comma or a space
	{
 	 //INSERT SOMETHING HERE
 	 $new_pos = strlen($lowercase_input);
	}
	else if($pos > $pos2)
	{
 	 	$new_pos = $pos;
	}
	else
	{
 	 	$new_pos = $pos2;
	}
	//echo "The new pos is: " . $new_pos  . "<br>";

	$fourth_section = substr($lowercase_input, $new_pos) . $fourth_section;
	//echo "The fourth section is: " . $fourth_section  . "<br>";
	
	if (check_if_zip ($fourth_section) == "FALSE")
	{
		//echo "This is not a zip code<br>";
	}
	else
	{
		//echo "This is a zip code. The five digit zip code is " . check_if_zip ($fourth_section) . "<br>";
		if ($extracted_zip == "")
		{
			$extracted_zip = check_if_zip ($fourth_section);
		}
		$found_in_current_section = 1;
	}
	
	if (check_if_country($fourth_section) == "FALSE")
	{
		//echo "This is not a country<br>";
	}
	else
	{
		//echo "This is a country. The country name is " . check_if_country($fourth_section) . "<br>";
		$found_in_current_section = 1;
	}
	
	if (check_if_state($fourth_section) == "FALSE")
	{
		//echo "This is not a state<br>";
	}
	else
	{
		//echo "This is a state. The state name is " . check_if_state($fourth_section) . "<br>";
		$found_in_current_section = 1;
	}
	
	if (check_if_city($fourth_section) == "FALSE")
	{
		//echo "This is not a city<br>";
	}
	else
	{
		//echo "This is a city. The city name is " . check_if_city($fourth_section) . "<br>";
		if ($extracted_city == "")
		{
			$extracted_city = $fourth_section;
		}
		$found_in_current_section = 1;
	}
	
	if ($pos2 == ($pos - 1))
	{
		//echo "The next character is a comma. Starting a new section.<br>";
  		$found_in_current_section = 1;
	}
	
	/*
	echo "looking at the character: " . substr($lowercase_input, ($new_pos - 1), 1) . "<br>";
	if (is_numeric(substr($lowercase_input, ($new_pos - 1), 1)))
	{
		echo "That character is numeric. Starting a new section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		echo "That character is not numeric.<br>";
	}
	*/
	
	if ($lowercase_input == "")
	{
		//echo "There is no text left. Skipping this section.<br>";
		$found_in_current_section = 1;
	}
	else
	{
		//echo "There is still text.<br>";
	}
	
	//echo "<br>";
}

//5 of 5

if ($pos2 == ($pos - 1))
{
  $new_pos = $pos2;
}
$lowercase_input = substr($lowercase_input, 0, $new_pos);
//echo "The remaining lowercase Input is now: " . $lowercase_input . "<br>";
$mystring = $lowercase_input;
$findme   = ' ';
$pos = strrpos_last($mystring, $findme);
if ($pos === false) {
    //echo "The string '$findme' was not found in the string '$mystring'";
} else {
    //echo "The string '$findme' was found in the string '$mystring'";
    //echo " and exists at position $pos";
}
//echo "<br>";
$mystring = $lowercase_input;
$findme   = ',';
$pos2 = strrpos_last($mystring, $findme);
if ($pos2 === false) {
    //echo "The string '$findme' was not found in the string '$mystring'";
} else {
    //echo "The string '$findme' was found in the string '$mystring'";
    //echo " and exists at position $pos2";
}
//echo "<br>";
if ($pos2 == ($pos - 1))
{
  $new_pos = $pos;
}
else if (($pos === false) && ($pos2 === false)) //if there isn't a comma or a space
{
  //INSERT SOMETHING HERE
  $new_pos = strlen($lowercase_input);
}
else if($pos > $pos2)
{
  $new_pos = $pos;
}
else
{
  $new_pos = $pos2;
}
//echo "The new pos is: " . $new_pos  . "<br>";

$fifth_section = substr($lowercase_input, 0, $new_pos);
//echo "The last section is: " . $fifth_section  . "<br>";
//echo "<br>";

	if (check_if_country($first_section) == "FALSE")
	{
		//echo "This is not a country<br>";
	}
	else
	{
		//echo "This is a country. The country name is " . check_if_country($first_section) . "<br>";
		$found_in_current_section = 1;
	}
	
	if (check_if_state($first_section) == "FALSE")
	{
		//echo "This is not a state<br>";
	}
	else
	{
		//echo "This is a state. The state name is " . check_if_state($first_section) . "<br>";
		$found_in_current_section = 1;
	}
	
	if (check_if_city($first_section) == "FALSE")
	{
		//echo "This is not a city<br>";
	}
	else
	{
		//echo "This is a city. The city name is " . check_if_city($first_section) . "<br>";
		$found_in_current_section = 1;
	}
//ACCEPT THIS AS THE STREET
		if ($fifth_section != "")
		{
			$extracted_street = $fifth_section;
		}
		else
		if ($fourth_section != "")
		{
			$extracted_street = $fourth_section;
		}
		else
		if ($third_section != "")
		{
			$extracted_street = $third_section;
		}
		else
		if ($second_section != "")
		{
			$extracted_street = $second_section;
		} 

	//echo "The first section is: " . $first_section  . "<br>";
	//echo "The second section is: " . $second_section  . "<br>";
	//echo "The third section is: " . $third_section  . "<br>";
	//echo "The fourth section is: " . $fourth_section  . "<br>";
	//echo "The fifth section is: " . $fifth_section  . "<br>";

	//echo "<br>";
	
	//echo "The street is: " . $extracted_street . "<br>";
	//echo "The city is: " . $extracted_city . "<br>";
	//echo "The state is: " . $extracted_state . "<br>";
	//echo "The zip is: " . $extracted_zip . "<br>";
	//echo "The country is: " . $extracted_country . "<br>";
	
	$xml_output = "<?xml version=\"1.0\"?>\n"; 
	
	$xml_output .= "<parsed_address>\n"; 

	$xml_output .= "<original_address>" . $input . "</original_address>\n";
	
	$xml_output .= "<street>" . $extracted_street . "</street>\n";
	
	$xml_output .= "<city>" . $extracted_city . "</city>\n";
	
	$xml_output .= "<state>" . $extracted_state . "</state>\n";

	$xml_output .= "<zip>" . $extracted_zip . "</zip>\n";
	
	$xml_output .= "<country>" . $extracted_country . "</country>\n";	 

	$xml_output .= "</parsed_address>";

	echo $xml_output;
	}
	

?>
