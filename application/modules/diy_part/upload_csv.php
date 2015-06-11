<?php 
/**
 * Albert Szczesny
 * @copyright 2014
 * Dictionaries/diy_parts/upload_csv.php
 */
 

require_once('../../../includes/initialize.php');

$session = new WidgetSession();
$session->Impress();

if(!$session->IsLoggedIn()){
    redirect_to("login");
}
$user = $session->getUserObject();
include_layout_template('tables_header.php');

?>

<body id="checkRMA_page">
<script>
	// wait for the DOM to be loaded 
    $(document).ready(function() {
		var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');
   
		$('form').ajaxForm({
			beforeSend: function() {
				status.empty();
				var percentVal = '0%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			success: function() {
				var percentVal = '100%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			complete: function(xhr) {
				status.html(xhr.responseText);
			}
		}); 

	});       
</script>
	<div id="main">
		<header><!-- Defining the header section of the page -->
            <div class="container">
				<h2>
					<u>Parts upload</u>
				</h2>
			</div>
		</header>
		<section id="content"><!-- Defining the main content section of the page -->
            <div class="main_container">
				<div id="divFileUpload">
					<form action="ajax/checkRMA.php" method="post" enctype="multipart/form-data">
						<label for="file">Filename:</label>
						<input type="file" name="file" id="file"><br>
						<input type="hidden" name="mimetype" value="csv" />
						<input type="submit" name="submit" value="Upload">
					</form>
				</div>
				<div class="progress">
					<div class="bar"></div >
					<div class="percent">0%</div >
				</div>
				<div id="status"></div>
			</div>
		</section>
	</div>
</body>
</html>
