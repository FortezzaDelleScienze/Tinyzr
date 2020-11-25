<?php
	require_once('./include.php');
	
	// verify the url data has been posted to this script
	if (isset($_POST['url']) && $_POST['url'] != '')
		$url = $_POST['url']; 
	else
		$url = '';
	
	
	// verify that a url was provided and that it is a valid url
	if ($url != '' && strlen($url) > 0){
		if ( validate_url($url) == true ){
			
			// create a connection to the database
			$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			
			// create all the variables to save in the database
			$id = '';
			$code = generate_code();
			$timestamp = time();
			$count = 0;
			
			$result = mysqli_query($conn, "INSERT INTO short_links VALUES ('$id', '$code', '$url', '$count', '$timestamp')");
			
			// verify that the new record was created
			$query = mysqli_query($conn, "SELECT * FROM short_links WHERE timestamp='$timestamp' AND code='$code'");
			if ($data = mysqli_fetch_assoc($query)){
				/* SUCCESS POINT */
				
				echo URL_BASE . $code;
			}
			else
				echo 'Unable to shorten your url';
		}
		else
			echo 'Please enter a valid url';
	}
	else
		echo 'No url was found';
	
?>