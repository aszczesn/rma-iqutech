<?php
/**
 * Albert Szczesny
 * @copyright 2014
 * dictionaries/diy_part/index.php
 */
 
require_once('../../../../includes/initialize.php');

$session = new WidgetSession();
$session->Impress();
$user = $session->getUserObject();
if(!$session->IsLoggedIn()){
    redirect_to(HOME_PAGE);
	exit;
}

    $type = $_POST['mimetype']; 
    $xhr = @$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 

	if($type == 'csv') {		
	
		$n = $_FILES['file']['name'];
		if (file_exists("../../upload/" . $_FILES["file"]["name"])) {
			  echo $_FILES["file"]["name"] . " already exists. <br /><hr />";
		} else {
			  move_uploaded_file($_FILES["file"]["tmp_name"],
			  "../../upload/" . $_FILES["file"]["name"]);
			  echo "Stored in: " . "../../upload/" . $_FILES["file"]["name"]."<br />";
		};
		
		$csvIterator = new CsvIterator("../../upload/" . $n);
		foreach ($csvIterator as $row => $data) {
			$part = new Part();
			if(strlen(trim($data[0])) > 0 && $row > 4){
				$part->partNumber = $data[0];
				$part->description = $data[1];
				$part->vendorId = 6;
				$part->created_at = strftime("%Y-%m-%d %H:%M:%S", time());
				$part->created_by =$user->login;
				$part->save();
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
	redirect_to("../");
?>