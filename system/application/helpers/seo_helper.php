<?php
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
						"¥",
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
?>