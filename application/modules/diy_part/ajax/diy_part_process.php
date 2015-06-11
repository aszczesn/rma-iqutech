<?php 
/**
 * Albert Szczesny
 * @copyright 2014
 * dictionaries/diy_part/index.php
 */
 
require_once('../../../../includes/initialize.php');

$session = new WidgetSession();
$session->Impress();

if(!$session->IsLoggedIn()){
    redirect_to(HOME_PAGE);
	exit;
}

		
if(isset($_POST['action'])){
	$action = $_POST['action'];
	//echo '<pre>';
	//var_dump($_POST);
	//echo '</pre>';
	//echo 'dupa' ;
}

$user = $session->getUserObject();
switch($action){
	    case "show": 
	        getList();
	        break;
	    case "getOne":
			if(isset($_POST["id"])){
				$id = $_POST["id"];
			}
	        getOne($id);
			break;	
		case "insert":
			$part = new Part();
			$part->partNumber = $_POST["partNumber"];
			$part->model = $_POST["model"];
			$part->description = $_POST["description"];
			$part->unitPrice = $_POST["unitPrice"];
			$part->vendorId = $_POST["vendorId"];
			$part->created_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$part->created_by = $user->login;
			$part->save();
			//echo "<pre>";
			//print_r($part);
			//echo "</pre>";
			break;
	        
	    case "delete":
			$part = Part::findById($_POST["id"]);
	        $part->delete();
	        break;
	        
	    case "update":	 
			$part = Part::findById($_POST["id"]);
			$part->partNumber = $_POST["partNumber"];
			$part->model = $_POST["model"];
			$part->description = $_POST["description"];
			$part->unitPrice = $_POST["unitPrice"];
			$part->vendorId = $_POST["vendorId"];
			$part->updated_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$part->updated_by = $user->login;
			$part->save();
	        break;
		default:
			exit;
					
	}
	
function getList(){
	$parts = Part::findAll();
	$arr=array();
	foreach($parts as $part){
		$vendor = Customer::findById($part->vendorId);
		$part->vendorName = $vendor->name;
		$arr[] = $part;
	}

	echo $json_response = json_encode($arr);
}

function getOne($id){
	$part = Part::findById($id);
	echo $json_response = json_encode($part);
}