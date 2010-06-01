<?php
/*=========================================================================================================
Copyright © Emprivo, Inc. All rights reserved.

Purpose: contains helpful functions for general use
=========================================================================================================*/



/*==================================
Checks return values from classes and functions for false

@param	all		required	data
@return	bool
==================================*/
function return_validation( $data )
{
	if ( !is_bool($data) || !$data === false )	# Note the ===
		return true;
	else
		return false;
}



/*==================================
Cleans data
==================================*/
function basic_clean( &$data )
{
	if ( !is_array($data) )
		$data = nvl( trim( $data ) );
}



/*==================================
Changes <br> to hard returns
==================================*/
function br2nl( $str )
{
   return preg_replace( "=<br */?>=i", "\n", $str );
}


/*==================================
Changes double hard returns to <p> tags
==================================*/
function nl2p( $str )
{
	return str_replace('<p></p>', '', '<p>' . preg_replace('#([\r\n]\s*?[\r\n]){2,}#', '</p>$0<p>', $str) . '</p>');
}



/*==================================
If data is not set, returns "NULL" (used for db insert queries to set NULL values)

@param	string	required	str
@param	bool	optional	add_quotes -- if true, add quotes and sends 'NULL'
==================================*/
function data_or_null( $str, $add_quotes = false )
{
	if ( trim($str) == "" || !isset($str) )
	{
		return "NULL";
	}
	else
	{
		if ( $add_quotes )
			$str = "'" . escape_quotes( trim( $str ) ) . "'";

		return $str;
	}
}



/*==================================
Similar to addslashes() in PHP, but prevents multiple slashes in db
==================================*/
function escape_quotes( $str )
{
	return addslashes( stripslashes( stripslashes( stripslashes( $str ) ) ) );
}



/*==================================
Checks if user's browser supports gzip compression (safari is excluded due to issues with it parsing gzip files)
==================================*/
function is_browser_gzip()
{
	if ( substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && !substr_count($_SERVER['HTTP_USER_AGENT'], 'Safari') )
		return true;
	else
		return false;
}



/*==================================
Returns user's ip address (used to get forwarded ip from proxy server else uses normal server variable)
==================================*/
function remote_addr()
{
	if ( $_SERVER['HTTP_X_FORWARDED_FOR'] != "" )
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		return $_SERVER['REMOTE_ADDR'];
}



/*==================================
Callback function for array_filter()
==================================*/
function remove_empty( $value )
{
	if ( trim($value) != "" )
		return true;
}



/*==================================
Removes certain number of characters from the end
==================================*/
function delete_tail( $str, $num_chars )
{
	return substr( $str, 0, strlen($str) - $num_chars );
}



/*==================================
Get first few words from a string based on maximum character length allowed

@param	string	required	str - the string to concat
@param	int		required	max_chars - maximum number of characters allowed
@param	bool	optional	ellipsis - if false doesn't add "..." at end of string
==================================*/
function limit_length( $str, $max_chars, $ellipsis = true )
{
	if ( strlen($str) > $max_chars )
	{
		$words = array_filter( explode( " ", $str ), "remove_empty" );

		# take first few words
		for ( $i = 0; strlen($final_str) < $max_chars; $i++ )
		{
			if( strlen( $final_str . $words[$i] ) > $max_chars )
				continue;
			else
				$final_str .= $words[$i] . " ";
		}

		# add ...
		if ( $ellipsis )
			$final_str = trim( $final_str ) . "...";

		return trim( $final_str );
	}
	else
		return trim( $str );
}



/*==================================
Converts an array of words into a string
==================================*/
function array_to_string( &$array )
{
	$str = "";
	foreach ( $array as $word )
	{
		$str .= $word . " ";
	}
	return trim($str);
}



/*==================================
Replace certain characters with hyphen (-) so that the url is valid and clean

@param	string	required	str
@param	string	optional	separator - the character used in place of the illegal characters
==================================*/
function make_url_friendly( $str, $separator = "-" )
{
	$illegal = array(	"&mdash;",
						"&quot;",
						"&amp;",
						"&copy;",
						"&laquo;",
						"&raquo;",
						"&ndash;",
						"&middot;",
						"&lsquo;",
						"&rsquo;",
						"&ldquo;",
						"&rdquo;",
						" ",
						"`",
						"~",
						"!",
						"@",
						"#",
						"$",
						"%",
						"^",
						"&",
						"*",
						"(",
						")",
						"--",
						"-",
						"-",
						"_",
						"__",
						"+",
						"=",
						"{",
						"}",
						"[",
						"]",
						";",
						":",
						"'",
						"´",
						"\"",
						",",
						".",
						"<",
						">",
						"/",
						"?",
						"\\",
						"|");

	$str = str_replace( $illegal, $separator, trim($str) );
	$str = preg_replace('/[^a-zA-Z0-9\s\-]/', '', $str);

	if( strpos( $str, "--" ) !== false )
		$str = make_url_friendly( $str, $separator );

	while ( substr( $str, strlen($str)-1, 1 ) == "-" )
					$str = substr( $str, 0, strlen($str)-1 );

	return $str;
}



/*==================================
Removes special characters like trademark and copyright symbols
==================================*/
function remove_special_characters( $str )
{
	$str = ereg_replace( 8226, " ", $str );	# bullet
	$str = ereg_replace( 149, " ", $str );	# bullet
	$str = ereg_replace( 8211, " ", $str );	# en dash
	$str = ereg_replace( 150, " ", $str );	# en dash
	$str = ereg_replace( 8212, " ", $str );	# em dash
	$str = ereg_replace( 151, " ", $str );	# em dash
	$str = ereg_replace( 8482, " ", $str );	# trademark
	$str = ereg_replace( 153, " ", $str );	# trademark
	$str = ereg_replace( 169, " ", $str );	# copyright mark
	$str = ereg_replace( 174, " ", $str );	# registration mark

	return trim( collapse_spaces( $str ) );
}



/*==================================
Replaces special characters with non-special equivalents
==================================*/
function normalize_special_characters( $str )
{
	# Quotes cleanup
	$str = ereg_replace( chr(ord("`")), "'", $str );		# `
	$str = ereg_replace( chr(ord("´")), "'", $str );		# ´
	$str = ereg_replace( chr(ord("„")), ",", $str );		# „
	$str = ereg_replace( chr(ord("`")), "'", $str );		# `
	$str = ereg_replace( chr(ord("´")), "'", $str );		# ´
	$str = ereg_replace( chr(ord("“")), "\"", $str );		# “
	$str = ereg_replace( chr(ord("”")), "\"", $str );		# ”
	$str = ereg_replace( chr(ord("´")), "'", $str );		# ´
	$str = ereg_replace( chr(ord("’")), "'", $str );		# ’

	$unwanted_array = array(	'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
								'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
								'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
								'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
								'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
	$str = strtr( $str, $unwanted_array );

	# Bullets, dashes, and trademarks
	$str = ereg_replace( chr(149), "&#8226;", $str );	# bullet •
	$str = ereg_replace( chr(150), "&ndash;", $str );	# en dash
	$str = ereg_replace( chr(151), "&mdash;", $str );	# em dash
	$str = ereg_replace( chr(153), "&#8482;", $str );	# trademark
	$str = ereg_replace( chr(169), "&copy;", $str );	# copyright mark
	$str = ereg_replace( chr(174), "&reg;", $str );		# registration mark

	return $str;
}



/*==================================
301 redirect
==================================*/
function permanent_redirect( $url )
{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $url);
	exit();
}



/*==================================
Checks if url already has http:// part. If not, appends to homepage_url

@param	string	required	url

@return	string
==================================*/
function absolute_or_relative_url( $url )
{
	if( strpos( $url, "http://" ) !== false )
		return $url;
	else
		return $_SESSION['homepage_url'] . $url;
}



/*==================================
If the ending decimal is ".00" or ".0", then return string without decimal point.
For example: "98.00" or "98.0" will get converted to "98".
==================================*/
function format_double( $str )
{
	if ( substr( $str, strlen($str)-3, 3 ) == ".00" )
		return delete_tail( $str, 3 );
	else if ( substr( $str, strlen($str)-2, 2 ) == ".0" )
		return delete_tail( $str, 2 );
	else
		return $str;
}



/*==================================
Converts a date string (default format: 10/31/2006)
==================================*/
function format_date( $str, $format = "n/j/Y" )
{
	$str = strtotime( $str );

	if ( $str != "" )
		return date( $format, $str );
	else
		return false;
}



/*==================================
Returns a timestamp (optionally takes a date and formats it as a timestamp)
==================================*/
function timestamp( $date = "" )
{
	if ( $date != "" )
		return date( "Y-m-d H:i:s", strtotime($date) );
	else
		return date( "Y-m-d H:i:s" );
}



/*==================================
Place contents of an array into a variable
==================================*/
function print_r_to_var( $array )
{
	ob_start();
	print_r($array);
	$b = ob_get_contents();
	ob_end_clean();
	return $b;
}



/*==================================
Used for error handling (database errors mostly).
Note: the verbal error handling sends the user to an error message page.
==================================*/
function handle_verbal_error( $error_str, &$query = "" )
{
	$debug = debug_backtrace();

	$error_str = 	"Error:\n" . $error_str .
					"\n\nBacktrace:\n" . print_r_to_var( $debug ) .
					"\n\nSQL Query:\n" . $query .
					"\n\nURL:\n" . "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}" .
					"\n\nVisitor's IP Address:\n" . remote_addr() .
					"\n\nTime Stamp:\n" . timestamp() .
					"\n\nSession Data:\n" . print_r_to_var( $_SESSION ) .
					"\n\nGET Data:\n" . print_r_to_var( $_GET ) .
					"\n\nPOST Data:\n" . print_r_to_var( $_POST );

	if ( stristr( $_SESSION['homepage_url'], 'http://localhost' ) === false )
	{	# live server
		mail( "error@" . $_SESSION['site_domain_name'], "URGENT: " . $_SESSION['site_name'] . " VERBAL Error", $error_str, "From: error@" . $_SESSION['site_domain_name'] . "\r\n" );

		# redirect user to friendly error page
		echo "<script type=\"text/javascript\">";
		echo "document.location='/oops';";
		echo "</script>";
	}
	else
	{	# dev server
		echo "<pre>";
		print_r ( $error_str );
		echo "</pre>";
	}

	exit();
}



/*==================================
Used for error handling
Note: the silent error handling doesn't let the user know about any problems
==================================*/
function handle_silent_error( $error_str, &$query = "" )
{
	$debug = debug_backtrace();

	$error_str = 	"Error:\n" . $error_str .
					"\n\nBacktrace:\n" . print_r_to_var( $debug ) .
					"\n\nSQL Query:\n" . $query .
					"\n\nURL:\n" . "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}" .
					"\n\nVisitor's IP Address:\n" . remote_addr() .
					"\n\nTime Stamp:\n" . timestamp() .
					"\n\nSession Data:\n" . print_r_to_var( $_SESSION ) .
					"\n\nGET Data:\n" . print_r_to_var( $_GET ) .
					"\n\nPOST Data:\n" . print_r_to_var( $_POST );

	if ( stristr( $_SESSION['homepage_url'], 'http://localhost' ) === false )
	{	# live server
		mail( "error@" . $_SESSION['site_domain_name'], $_SESSION['site_name'] . " Silent Error", $error_str, "From: error@" . $_SESSION['site_domain_name'] . "\r\n" );
	}
	else
	{	# dev server
		echo "<pre>";
		print_r ( $error_str );
		echo "</pre>";
	}
}



/*==================================
Returns numbers of days left to given date

@param	date	required	date

@return	int
==================================*/
function get_days_to_date( $date )
{
	return abs( round( ( strtotime($date) - strtotime(date("Y-m-d")) ) / (60 * 60 * 24) ) );
}



/*==================================
Removed multiple spaces with a single space
==================================*/
function collapse_spaces( $str )
{
	return eregi_replace("[[:space:]]+", " ", $str);
}



/*==================================
If input is undefined, set default value
==================================*/
function set_default( &$var, $default = "" )
{
	if ( !isset($var) || $var == "" )
		$var = $default;
}



/*==================================
If input is undefined, return default value
==================================*/
function nvl( $var, $default = "" )
{
	return isset($var) ? $var : $default;
}



/*==================================
Prints input with the HTML characters (like "<", ">", etc.) properly quoted
==================================*/
function pv( $var )
{
	echo isset($var) ? htmlSpecialChars(stripslashes($var)) : "";
}



/*==================================
If data is not in _GET, obtain data from _POST
==================================*/
function GET_or_POST( $var )
{
	if ( trim(nvl($_GET[$var])) != "")
		return trim(nvl($_GET[$var]));
	else
		return trim(nvl($_POST[$var]));
}



/*==================================
Get current url in browser
==================================*/
function get_browser_url()
{
	return "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}



/*=============================================
Print error list

@param		array	required	errors
=============================================*/
function print_errors( $errors )
{
	if ( count($errors) )
	{
		echo "<ul class='dark_red vertical_space_22'>";

		foreach ( $errors as $value )
			echo "<li>" . $value . "</li>";

		echo "</ul>";
	}
}



/*==================================
Checks to see if an email address is valid -- allows all country extensions
==================================*/
function is_email( $email )
{
	$matches = 	"/^[-_.+[:alnum:]]+@((([[:alnum:]]|" .
				"[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|".
				"af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|".
				"bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|".
				"bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|".
				"cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|".
				"er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|".
				"gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|".
				"hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|".
				"kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|".
				"lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|".
				"mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|".
				"nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|".
				"pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|".
				"sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|".
				"sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|".
				"ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|".
				"yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|".
				"[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|".
				"[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i";

	return  preg_match( $matches, $email );
}



/*==================================
Replaces restricted words with *

@param	string	required	str

@return	string
==================================*/
function replace_restricted_words( $str )
{
	$words = array_filter( explode( " ", $str ), "remove_empty" );

	foreach( $words as $key => $value )
	{
		$checkQ = db_query("SELECT 1
							FROM
								emprivo.restricted_words
							WHERE
								word = '" . escape_quotes( $value ) . "'");
		if( db_num_rows( $checkQ ) )
		{
			for( $i = 0; $i < strlen($value); $i++ )
				$stars .= "*";

			$words[$key] = $stars;
		}
	}

	return array_to_string( $words );
}



/*==================================
Checks a string for restricted words

@param	string	required	str

@return	bool
==================================*/
function check_restricted_words( $str )
{
	$str = make_url_friendly( $str, " " );
	$words = array_filter( explode( " ", $str ), "remove_empty" );

	foreach( $words as $value )
	{
		$checkQ = db_query("SELECT 1
							FROM
								emprivo.restricted_words
							WHERE
								word = '" . $value . "'");
		if( db_num_rows($checkQ) )
			return false;
	}

	# no restricted words found
	return true;
}



/*==================================
Checks if a url is valid

@param		string		required	url
@param		array		optional	error_strings -- an array of error messages to check the opened page for
											ex: "merchant is no longer participating in the affiliate program"
==================================*/
function is_url_valid( $url, $error_strings = array() )
{
	# data validation
	data_validation( $url, "|required|", "silent" );


	# check if url is empty
	if ( $url == "" )
		return false;

	# get url data
	$url_data = get_url( $url );
	$content = $url_data[0];
	$response = $url_data[1];
	$response_code = $response['http_code'];

	# check response code
	if (	$response_code == "400" ||
			$response_code == "401" ||
			$response_code == "402" ||
			$response_code == "403" ||
			$response_code == "404" ||
			$response_code == "411" ||
			$response_code == "500" ||
			$response_code == "501" ||
			$response_code == "502" ||
			$response_code == "0")
	{
		return false;
	}

	# check if url contains 404 or 'localhost'
	if ( stristr( $response['url'], "/404" ) || stristr( $response['url'], "/localhost" ) )
	{
		return false;
	}

	$content = strtolower( trim( collapse_spaces( strip_tags( $content ) ) ) );

	# do regexp matching
	foreach( $error_strings as $value )
	{
		if ( eregi( $value, $content ) )
		{	# broken link
			return false;
		}
	}

	# good link
	return true;
}



/*==================================
Removes whitespace before serving page to speed up page loads for visitor
==================================*/
function remove_whitespace( $data )
{
	if ( strpos( $data, "<html" ) !== false )
	{	# html content

		# remove newlines
		#$data = preg_replace("/\r\n|\n|\r/", " ", $data);

		# remove multiple spaces
		#$data = eregi_replace("[[:space:]]+", " ", $data);

		# remove newlines, tabs, multiple spaces
		$data = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '   ', '    '), ' ', $data);

		# remove space between tags
		$data = preg_replace('/>\s+</m', '><', $data);

		return trim($data);
	}
	else
		return $data;
}



/*==================================
Cleans and validates data

@param	string/array	required	data
@param	string			required	validations
@param	string			optional	error_handling -- specifies if error handling function should be called ("verbal" or "silent")

@return	bool
==================================*/
function data_validation( &$data, $validations, $error_handling = "" )
{
	/*
			-----------------------------------------------------------------------------------------------
			Validations							Syntax							Input Data
			-----------------------------------------------------------------------------------------------
			required							|required|						for both arrays and strings
			optional							|optional|						for both arrays and strings
			binary (0 or 1)						|binary|						strings
			is_int								|int|							strings
			is_email							|email|							strings
			check restricted words				|check_restricted_words|		strings
			regexp								|regexp:[a-z]|					strings

			min words							|min_words:x|					strings
			max words							|max_words:x|					strings

			min value							|min_value:x|					strings
			max value							|max_value:x|					strings

			min length							|min_length:x|					strings
			max length							|max_length:x|					strings

			min array count						|min_elements:x|				array
			max array count						|max_elements:x|				array

			value in							|value_in:x__x__x|				strings
			value not in						|value_not_in:x__x__x|			strings
	*/

	# set if data is an array
	if ( is_array($data) )
		$is_array = true;

	# clean input data
	basic_clean( $data );

	# get list of validations to perform
	$validations = array_filter( explode( "|", trim($validations) ), "remove_empty" );

	# reset is_error flag
	$is_error = false;

	# perform validations
	foreach( $validations as $check )
	{
		if( trim($check) != "" )
		{
			switch( true )
			{
				case substr( $check, 0, 8 ) == 'optional':
								if( $is_array && !count($data) )
									return true;						# data's optional and not available, so skip all checks
								else if( !$is_array && $data == "" )
									return true;						# data's optional and not available, so skip all checks
								break;

				case substr( $check, 0, 8 ) == 'required':
								if( $is_array && !count($data) )
									$is_error = true;
								else if( !$is_array && $data == "" )
									$is_error = true;
								break;

				case substr( $check, 0, 6 ) == 'binary':
								if( $data != 0 && $data != 1 )
									$is_error = true;
								break;

				case substr( $check, 0, 3 ) == 'int':
								if( !eregi( "^[0-9-]+$", $data ) )
									$is_error = true;
								break;

				case substr( $check, 0, 5 ) == 'email':
								if( !is_email( $data ) )
									$is_error = true;
								break;

				case substr( $check, 0, 22 ) == 'check_restricted_words':
								if( !check_restricted_words( $data ) )
									$is_error = true;
								break;

				case substr( $check, 0, 6 ) == 'regexp':
								if( !eregi( substr($check,7,strlen($check)-7), $data ) )
									$is_error = true;
								break;

				case substr( $check, 0, 9 ) == 'min_value':
								if( !eregi( "^[0-9-]+$", $data ) || $data < substr($check,10,strlen($check)-10) )
									$is_error = true;
								break;

				case substr( $check, 0, 9 ) == 'max_value':
								if( !eregi( "^[0-9-]+$", $data ) || $data > substr($check,10,strlen($check)-10) )
									$is_error = true;
								break;

				case substr( $check, 0, 9 ) == 'min_words':
								if( count( array_filter ( explode( " ", $data ), "remove_empty" ) ) < substr($check,10,strlen($check)-10) )
									$is_error = true;
								break;

				case substr( $check, 0, 9 ) == 'max_words':
								if( count( array_filter ( explode( " ", $data ), "remove_empty" ) ) > substr($check,10,strlen($check)-10) )
									$is_error = true;
								break;

				case substr( $check, 0, 10 ) == 'min_length':
								if( strlen($data) < substr($check,11,strlen($check)-11) )
									$is_error = true;
								break;

				case substr( $check, 0, 10 ) == 'max_length':
								if( strlen($data) > substr($check,11,strlen($check)-11) )
									$is_error = true;
								break;

				case substr( $check, 0, 12 ) == 'min_elements':
								if( count($data) < substr($check,13,strlen($check)-13) )
									$is_error = true;
								break;

				case substr( $check, 0, 12 ) == 'max_elements':
								if( count($data) > substr($check,13,strlen($check)-13) )
									$is_error = true;
								break;

				case substr( $check, 0, 8 ) == 'value_in':
								$values = substr( $check, 9, strlen($check)-9 );
								$values = array_filter( explode( "__", $values ), "remove_empty" );
								$value_in = false;

								foreach( $values as $value )
									if( $data == $value )
										$value_in = true;

								if( $value_in == false )
									$is_error = true;
								break;

				case substr( $check, 0, 12 ) == 'value_not_in':
								$values = substr( $check, 13, strlen($check)-13 );
								$values = array_filter( explode( "__", trim($values) ), "remove_empty" );
								$value_not_in = true;

								foreach( $values as $value )
									if( $data == $value )
										$value_not_in = false;

								if( $value_not_in == false )
									$is_error = true;
								break;
			}
		}
	}

	if( $is_error )
	{	# errors found

		if( $error_handling == "verbal" )
			handle_verbal_error( "RED ALERT: invalid data=" . $data . " checks=" . array_to_string($checks) );
		else if( $error_handling == "silent" )
			handle_silent_error( "WATCH: invalid data=" . $data . " checks=" . array_to_string($checks) );

		# DONT REMOVE THIS RETURN -- class functions use it for case logic
		return false;
	}
	else
	{	# no errors
		return true;
	}
}



/*==================================
Prints out error
==================================*/
function print_error( $title = "", $error = "" )
{
	echo "<span class='stylish error'><strong>" , $title,  "</strong> " , $error,  "</span><br /><br />";
}



/*==================================
Prints out title
==================================*/
function print_title( $title, $description = "" )
{
	echo "<span class='title'>" , $title , "</span><br />";

	if ( $description != "" )
		echo "<span class='sub_title'>" , $description , "</span><br /><br />";
	else
		echo "<br />";
}



/*==================================
Clean discount detail values -- remove unwanted characters
==================================*/
function clean_discount_values( &$value )
{
	$value = str_replace( array(",", " ", "$", "%"), "", $value );
}



/*==================================
Get list of directory contents via FTP

@param	string	required	server
@param	string	required	username
@param	string	required	password
@param	string	optional	directory - changed to this directory on server
@param	string	optional	num_tries - number of times to try getting the list

@return	array[0]	ftp_id
		array[1]	array of file names
==================================*/
function ftp_ls( $server, $username, $password, $directory = "", $num_tries = 1 )
{
	# data validation
	data_validation( $server, "|required|", "verbal" );
	data_validation( $username, "|required|", "verbal" );
	data_validation( $password, "|required|", "verbal" );
	data_validation( $directory, "|optional|" );


	for( $i = 1; !isset( $files ) && !is_array( $files ); $i++ )
	{
		$ftp_id = ftp_connect( $server );
		ftp_login( $ftp_id, $username, $password );

		if ( $directory != "" )
			ftp_chdir( $ftp_id, $directory );

		$files = ftp_nlist( $ftp_id, "." );

		if ( $num_tries != 1 )
			sleep(10);

		if ( $i >= $num_tries )
			continue;
	}

	return array( $ftp_id, $files );
}



/*==================================
Get list of directory contents

@param	string	required	directory - changed to this directory on server
@param	string	optional	type - specifies what to return (files, directories, or both)
									1 = directories
									2 = files
									0 (default) = both

@return	array	file names
==================================*/
function dir_ls( $directory, $type = 0 )
{
	# data validation
	data_validation( $directory, "|required|", "verbal" );
	data_validation( $type, "|optional|value_in:0__1__2|", "verbal" );


	$list = array();
	$handle = opendir( $directory );

	while( $file = readdir( $handle ) )
	{
		if ( $file == ".svn" )
		{	# skip .svn directories, they are created by subversion
			continue;
		}

		if ( $type == 1 && !is_dir( $directory . $file ) )
		{	# only directories needed and current item is a file
			continue;
		}

		if ( $type == 2 && is_dir( $directory . $file ) )
		{	# only files needed and current item is a directory
			continue;
		}

		if ( $file != "." && $file != ".." )
			array_push( $list, $file );
	}

	closedir( $handle );

	if ( count($list) )
		return $list;
	else
		return false;
}



/*==================================
Get a file's extension
==================================*/
function get_file_extension( $file_name )
{
	# data validation
	data_validation( $file_name, "|required|", "verbal" );

	$extension = explode( ".", $file_name );
	return strtolower( end( $extension ) );
}



/*==================================
Extract domain name from a URL
==================================*/
function get_domain_name( $url )
{
	# data validation
	data_validation( $url, "|required|", "verbal" );

	eregi( "http[s]*://([a-zA-Z0-9.-]*)/?.*", $url, $domain );
	$domain = explode( ".", $domain[1] );

	if ( strlen( end($domain) ) == 2 && ( strlen($domain[count($domain)-2]) == 3 || strlen($domain[count($domain)-2]) == 2 )  )
	{
		# special case domains -- ex: co.uk .in .ca
		return strtolower( $domain[count($domain)-3] . "." . $domain[count($domain)-2] . "." . end( $domain ) );
	}
	else
	{
		# regular .com type domains -- three or more letters
		return strtolower( $domain[count($domain)-2] . "." . end( $domain ) );
	}
}



/*==================================
Get url content and response headers (given a url, follows all redirections on it and returned content and response headers of final url)

@return	array[0]	content
		array[1]	array of response headers
==================================*/
function get_url( $url,  $javascript_loop = 0, $timeout = 15 )
{
	$url = str_replace( "&amp;", "&", trim($url) );
	# NOTE decode_url was removed because some URL have encoded strings that must remain (ex: some CJ links)

	$cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_ENCODING, "" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );	# required for https urls
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
	$content = curl_exec( $ch );
	$response = curl_getinfo( $ch );
	curl_close ( $ch );

	if ($response['http_code'] == 301 || $response['http_code'] == 302)
	{
		ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

		if ( $headers = get_headers($response['url']) )
		{
			foreach( $headers as $value )
			{
				if ( substr( strtolower($value), 0, 9 ) == "location:" )
				{
					$new_url = trim( substr( $value, 9, strlen($value) ) );

					# if relative url create full url
					if ( substr( $new_url, 0, 1 ) == "/" )
					{
						eregi( "http[s]*://([a-zA-Z0-9.-]*)/?.*", $response['url'], $temp_url );
						$new_url = $temp_url[1] . $new_url;
					}

					return get_url( $new_url );
				}
			}
		}
	}


	if (	( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) &&
			$javascript_loop < 5
	)
	{
		return get_url( $value[1], $javascript_loop+1 );
	}
	else
	{
		return array( $content, $response );
	}
}



/*==================================
Get the first line in a csv file
==================================*/
function get_first_csv_line( $file, $delimiter )
{
	if ( file_exists( $file ) )
		$fp = fopen( $file, "r" );
	else
		return false;

	while ( !is_array( $line ) && !feof( $fp ) )
	{
		$line = fgetcsv( $fp, 0, $delimiter );
	}

	fclose( $fp );

	if ( is_array( $line ) )
		return $line;
	else
		return false;
}



/*==================================
Remove a line from the end of a file
==================================*/
function truncate_file( $file )
{
	if ( file_exists( $file ) )
		$fp = fopen( $file, "r+" );
	else
		return false;

	while ( true )
	{
		$last_addr = $addr;
		$addr = ftell( $fp );
		if ( !@fgets( $fp, 2048 ) )
			break;
	}
	ftruncate( $fp, $last_addr );

	fclose( $fp );

	# all done
	return true;
}



/*==================================
Writes to an error log
==================================*/
function write_error_log( $file_pointer, $message )
{
	fputs( $file_pointer, timestamp() . "\n" . $message . "\n\n----------\n\n" );
}



/*==================================
Recursively create directory path
==================================*/
function mkdirr( $path_name, $mode = null )
{
	# check if directory already exists
	if (is_dir($path_name) || empty($path_name))
		return true;

	# ensure a file does not already exist with the same name
	$path_name = str_replace( array('/', '\\'), DIRECTORY_SEPARATOR, $path_name );
	if ( is_file($path_name) )
	{
		trigger_error('mkdirr() File exists', E_USER_WARNING);
		return false;
	}

	# crawl up the directory tree
	$next_path_name = substr( $path_name, 0, strrpos($path_name, DIRECTORY_SEPARATOR) );

	if ( mkdirr( $next_path_name, $mode ) )
	{
		if ( !file_exists($path_name) )
			return mkdir( $path_name, $mode );
	}

	return false;
}



/*==================================================================
Removes unwanted characters (that are not on a keyboard) from a string

@params		string	required	str

@return		string
==================================================================*/
function character_cleanup( $str )
{
	# data validation
	data_validation( $str, "|required|" );


	return trim( collapse_spaces( eregi_replace( "[^[:alnum:]!@#$%&*()-_+=:;'\",.<>?/\]", " ", $str ) ) );
}



/*==================================================================
Removed duplicate items from a string is glued together with a separator

@params		string	required	str

@return		string
==================================================================*/
function unique_elements( $str, $separator = "|" )
{
	# data validation
	data_validation( $str, "|required|" );

	if ( $str == "" )
		return "";

	$elements = array_unique( explode( $separator, $str ) );
	$elements = array_filter( $elements, "remove_empty" );	# remove blank elements

	if ( count( $elements ) )
		return $separator . implode( $separator, $elements ) . $separator;
	else
		return "";
}



/*========================================================
Convert barcode into a UPC-A or EAN-13 format

@param		string		required		barcode - contains upc code
@param		string		optional		format - "upca" or "ean13"

@return 	converted barcode number
==========================================================*/
function format_barcode( $barcode, $format = "ean13" )
{
	# data validation
	data_validation( $format, "|optional|value_in:upca__ean13", "verbal" );
	data_validation( $barcode, "|required|int|min_length:12|max_length:13|", "verbal");


	# check desired length
	switch ( $format )
	{
		case "upca":
			$desired_length = 12;
			break;

		case "ean13":
			$desired_length = 13;
			break;
	}


	# return desired barcode type
	if ( $desired_length == strlen($barcode) )
	{	# already in the correct format
		return $barcode;
	}
	else if ( $desired_length < strlen($barcode) )
	{	# convert from ean13 to upca
		return substr( $barcode, 1, strlen( $barcode ) );
	}
	else if ( $desired_length > strlen($barcode) )
	{	# convert from upca to ean13
		return '0' . $barcode;
	}
}

?>