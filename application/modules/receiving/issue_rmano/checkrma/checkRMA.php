<?php
/**
 * Albert Szczesny
 * @copyright 2014
 * receiving/checkrma/checkRMA.php
 */
 
require_once('../../../../includes/initialize.php');

$session = new WidgetSession();
$session->Impress();
include_layout_template('header.php');

if(!$session->IsLoggedIn()){
    redirect_to(HOME_PAGE);
	exit;
}

    $type = $_POST['mimetype']; 
    $xhr = @$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 

	if($type == 'csv') {		
	
		$n = $_FILES['file']['name'];
		if (file_exists("../upload/" . $_FILES["file"]["name"])) {
			  echo $_FILES["file"]["name"] . " already exists. Overriding. <br /><hr />";
			  move_uploaded_file($_FILES["file"]["tmp_name"],"../upload/" . $_FILES["file"]["name"]);
		} else {
			  move_uploaded_file($_FILES["file"]["tmp_name"],"../upload/" . $_FILES["file"]["name"]);
			  echo "Stored in: " . "../upload/" . $_FILES["file"]["name"]."<br />";
		};
		$rma = Rma::generateRMANo();
		$out = "";
		$outError = "";
		
		$prevPartNumber="";
		$csvIterator = new CsvIterator("../upload/" . $n);
		foreach ($csvIterator as $row => $data) {
			if(strlen(trim($data[0])) > 0 && $row > 15){
				$sql = "select * "
						."from part where partNumber= '" . $data[1] ."' AND vendorID = 6 ";
				$part = array_shift(Part::findBySql($sql));
				//echo "<pre>";
				//print_r($part);
				//echo "</pre>";
				
				if(empty($part)){
					if($prevPartNumber == ""){
						$prevPartNumber = $data[1];
						$outError = "<p>The RMA contains part number: <b>" .  $data[1] . "</b> which is not in the Pegatron master list.</p>";
					}else if($prevPartNumber != $data[1]){
						$outError = "<p>The RMA contains part number: <b>" .  $data[1] . "</b> which is not in the Pegatron master list.</p>";
					}
				} else {
					if($prevPartNumber == ""){
						$prevPartNumber = $part->partNumber;
						$out = "<p>The RMA contains part number: <b>" . $part->partNumber. "</b> which is in Pegatron master list. " . $part->description . "</p>";
					} else if($prevPartNumber != $part->partNumber){
						$out = "<p>The RMA contains part number: <b>" . $part->partNumber. "</b> which is in Pegatron master list. " . $part->description . "</p>";
					}
				}
			}
		}
		echo $out.$outError;
	}  else { 
        // return text var_dump for the html request 
        echo "VAR DUMP:<p />"; 
        var_dump($_POST); 
        foreach($_FILES as $file) { 
            $n = $file['name']; 
            $s = $file['size']; 
            if (!$n) continue; 
            echo "File: $n ($s bytes)"; 
        } 
    } 
?>