<?php
/**
 * Albert Szczesny
 * @copyright 2014
 * receiving/checkrma/checkRMA.php
 */
 
require_once('../../../includes/initialize.php');

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
			  echo $_FILES["file"]["name"] . " already exists. <br /><hr />";
		} else {
			  move_uploaded_file($_FILES["file"]["tmp_name"],
			  "../upload/" . $_FILES["file"]["name"]);
			  echo "Stored in: " . "../upload/" . $_FILES["file"]["name"]."<br />";
		};
		
		$csvIterator = new CsvIterator("../upload/" . $n);
		foreach ($csvIterator as $row => $data) {
			if(strlen(trim($data[0])) > 0 && $row > 15){
				$sqlVer = "select count(partno) as partCount, description as de "
						."from partnumbers where partno = '" . $data[1] ."' AND company = 'Pegatron' "
						."GROUP BY partno, description";
				echo $sqlVer .'<br />';
			}
		}
	}
    else { 
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