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
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
				
			$numUrl=check_double_url($url,$conn);

			if($numUrl > 0){
				
				mysqli_close($conn);
				echo 'url già esistente - url non inserito';
			}
			else{
				$code = generate_code($conn);
				if($code=="esiste"){
					echo 'esiste';
				}
				else{
					$count = 0;
					$sql="INSERT INTO `shortlinks`(`code`, `url`, `counting`) VALUES ('$code','$url','$count')";
					$a=$conn->query($sql);
					$sql2="SELECT * FROM `shortlinks` where code='$code'";
					if ($result = mysqli_query($conn, $sql2)) {
						while ($row = mysqli_fetch_assoc($result)) {
							$count++;
						}
						echo URL_BASE . $code;
						echo $count;

					}
					else{
						echo 'no data';
					};
				}
				mysqli_close($conn);
			}
		}
		else{
			echo 'Inserisci un url valido';
		}
	}
	else{
		echo 'Nessun url trovato';
	}
	
?>