<?php
	
	// require the include files that has all the backend functionality
	require_once('./include.php');
	
	// check to see if a code has been supplied and process it
	if (isset($_GET['code']) && $_GET['code'] != '' && strlen($_GET['code']) > 0){
		
		
		$code = $_GET['code']; 
		
		// validate the code against the database
		$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		$query = mysqli_query($conn, "SELECT * FROM short_links WHERE code='$code'");
		if (mysqli_num_rows($query) == 1){
			
			// retrieve the data from the database
			$data = mysqli_fetch_assoc($query);
			
			// update the coutner in the database
			mysqli_query($conn, "UPDATE short_links SET count='" . ($data['count']) + 1 . "' WHERE id='". ($data['id'])."'");
			
			/* ADD EXTRA STUFF HERE IF DESIRED */
			
			// set some header data and redirect the user to the url
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Cache-Control: no-cache");
			header("Pragma: no-cache");

			header("Location: " . $data['url']);
			
			die();
		}
		else
			$message = '<font color="red">Non riesco a reindirizzare al tuo url</font>';
	}
?>
<html>
	<head>
		<title>Tinyzr.com | a simple url shortener</title>
		<link rel="stylesheet" type="text/css" href="./main.css"></link>
		<script type="text/javascript" src="./jquery-3.3.1.min.js"></script>
		
		<script>
			// ready the sites javascript for use after the page has loaded
			$(document).ready(function(){
				
				// process the form submission using javascript
				$("#lesnForm").submit(function(event){
					
					// get the url to be shortened
					var url = $("#url").val();
					var result="0";
					if ($.trim(url) != ''){
						
						// submit all of the required data via post to the processing script
						$.post("./process.php", {url:url}, function(data){
							console.log(data);
							// process the returned data from the post
							if (data == '1'){
								$("#url").val(url).focus();
								
								// display a success message to the user
								$("#message").html('Your link has been shortened!');
								var counter = $("#counter").text();
								
								
								$("#counter").text(parseInt(counter) + 1);
								
								
								// update the counter shown on the page
								
							}
							else{
								$("#message").html(data);
							}
							
						})
						
					}
					
					
					// select the text box after form submission
					$("#url").focus();
					
					// prevent the form from reloading the page
					return false;
				});
				
				// select the text box on page load
				$("#url").focus();
			});
		</script>
		
	</head>
	
	<body>
		
		<div id="wrapper">
			<div id="header">
				<h1><a href="https://tinyzr.com">Tinyzr.com</a> <span class="seperator"> | </span> <span class="moto"> a simple url shortener</span></h1>
			</div>
			<div id="content">
				<h3 class="topper">Immetti un link e clicca sul pulsante <i><u>"tinyzr me"</u></i>.<br>E' semplice!</h3>
				
				<table class="mainForm"><form method="post" action="#" name="lesnForm" id="lesnForm">
					<tr>
						<td align="right"><input style="width: 100%;" type="text" name="url" id="url" value="" placeholder="http://" /></td>
						<td align="left" width="1"><input type="submit" name="lesnBtn" value="tinyzr me" /></td>
					</tr>
				</table></form>
				
				<h3 id="message" class="success"><?php echo ( isset($message) ? $message : '' );  ?></h3>
				<?php $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);?>
				<div class="meta">
					Ci sono attualmente <b id="counter"><?php echo number_format(count_urls('',$conn)); ?></b> url in Tinyzr .
				</div>
				
			</div>
			<div id="footer">
				<div class="right">
					<a target="_blank" href="https://truebutcoin.biz">Truebitcoin</a>
				</div>
                
                <a target="_blank" href="https://tinyzr.com">Tinyzr.com</a>
					
			</div>
		
		
		    <br />
		
		</div>
		
	</body>
</html>
