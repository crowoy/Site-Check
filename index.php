<?php

	include 'includes/head.php';
	
	function SiteCheck ($URL) {
		$curl_session = curl_init($URL);
		
		curl_setopt($curl_session, CURLOPT_NOBODY, TRUE);
		curl_setopt($curl_session, CURLOPT_FOLLOWLOCATION, TRUE);
		
		curl_exec($curl_session);
		
		$status_code = curl_getinfo($curl_session, CURLINFO_HTTP_CODE);
		
		return ($status_code === 200) ? TRUE : FALSE;
	}
	
	function www ($URL) {
		$URL = substr($URL, 1, 3);
		if ($URL === 'ww.') {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	if (isset ($_POST['URL']) == TRUE && empty ($_POST['URL']) == FALSE) {
		$URL = trim($_POST['URL']);
		//$URL = $_POST['URL'];
		
		if (filter_var($URL, FILTER_VALIDATE_URL) == TRUE || www($URL) == TRUE) {
			//include 'includes/scrolling_code.php'; 
			if (SiteCheck($URL) == TRUE) {
				sleep (0.5);
				?>
				<script type="text/javascript">
						alert("Site is currently running.");
				</script><?php
			} else {
				?><script type="text/javascript">
						alert("Site is currently unavaliable/unreachable/non-existent.");
				</script><?php
			}
		} else {
				?><script type="text/javascript">
						alert("Invalid URL. \n\n(Must have \"www.\" or \"http:\/\/\")");
				</script><?php
		};
	?>
	<meta http-equiv="refresh" content="0;url=index.php" />
	Please wait...
	<?php
	}

include 'includes/body.php';

include 'includes/footer.php';?>