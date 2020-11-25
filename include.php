<?php
	
	// define the global variables for the site
	define("URL_LENGTH", 7);
	define("URL_BASE", 'http://tinyzr.com/');
	
	// set the global variables for the database connection
	define("DB_SERVER", 'localhost');
	define("DB_USER", 'admin');
	define("DB_PASS", 'admin');
	define("DB_NAME", 'tinyzr');
	
	// defene the character set for the url codes to generate from
	define("CHARSET", "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
	
	// function for creating the random string for the unique url
	function generate_code(){
		
		// create the charset for the codes and jumble it all up
		$charset = str_shuffle(CHARSET);
		$code = substr($charset, 0, URL_LENGTH);
		
		// verify the code is not taken
		while (count_urls($code) > 0)
			$code = substr($charset, 0, URL_LENGTH);
		
		// return a randomized code of the desired length
		return $code;
	}
	
	
	// function to count the total number of short urls saved on the site
	function count_urls($code = ''){
		
		// build the extra query string to seach for a code in the database
		if ($code != '')
			$extra_query = " WHERE code='$code'";
		else
			$extra_query = "";
		
		// connect to the database
		$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		
		// count ow many total shortened urls have been made in the database and return it
		$count = (int) mysqli_num_rows(mysqli_query($conn, "SELECT * FROM short_links " . $extra_query));
		return $count;
	}
	
	// function to perform all the validation needed for the urls provided
	function validate_url($url){
		
		// make sure the user isn't trying to shorten one of our urls
		if (substr($url, 0, strlen(URL_BASE)) != URL_BASE){
			return filter_var($url, FILTER_VALIDATE_URL);
		}
		else
			return false;
	}
	
	
?>