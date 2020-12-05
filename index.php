<?php
	
	// require the include files that has all the backend functionality
	session_start();

	require_once('./include.php');
	$uri=($_SERVER['REQUEST_URI']);
	$arrCode=explode('?',$uri);
	$code="";
	
	if(is_array($arrCode) && count($arrCode) >1 && !empty($arrCode)){
		$code=$arrCode[1];
	}

	// check to see if a code has been supplied and process it
	if ($code!=""){
		
		
		
		
		// validate the code against the database
		$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		$query = "SELECT * FROM shortlinks WHERE code='$code'";
		$count=0;
		$urlTo="";
		if ($result = mysqli_query($conn, $query)) {
			while ($row = mysqli_fetch_assoc($result)) {
				$urlTo=$row["url"];
				$count++;
			}
			
			
			if($count===1){
					
			// retrieve the data from the database
			
			
			// set some header data and redirect the user to the url
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Cache-Control: no-cache");
			header("Pragma: no-cache");

			header("Location: " . $urlTo);
			
			die();
			}
		}
		else
			$message = '<font color="red">Non riesco a reindirizzare al tuo url</font>';
	}
	$username="";
	if(isset($_SESSION["username"])){
		$class="enabled";
	}
	else{
		$class="disabled";
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
				<?php if ($class=="enabled") 
				{?>
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
				<?php
				}
				else{
				?>	
				jQuery(document).on("click","#lesnBtn",function(event){
					event.preventDefault();
					$("#message").html("EFFETTUA IL LOGIN PER CONTINUARE");
					return false;
				})
				<?php
				}
				?>

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
						<td align="left" width="1"><input type="submit" id="lesnBtn" name="lesnBtn" value="tinyzr me" /></td>
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