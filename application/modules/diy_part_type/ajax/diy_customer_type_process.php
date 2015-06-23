<?php 
/**
 * Albert Szczesny
 * @copyright 2014
 * admin/CustomerType/index.php
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
}
if(isset($_POST["id"])){
	$id = $_POST["id"];
}
$user = $session->getUserObject();

switch($action){
	    case "show": 
	        getList();
	        break;
	    case "getOne":
	        getOne($id);
	    break;
		case "insert":
			$customerType = new CustomerType();
			$customerType->name = $_POST["name"];
			$customerType->created_by = $user->login;
			$customerType->created_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$customerType->save();
	    break;
	        
	    case "delete":
			$customerType = CustomerType::findById($_POST["id"]);
	        $customerType->delete();
	        
	        break;
	        
	    case "update":	 
			$customerType = CustomerType::findById($id);
			$customerType->name = $_POST["name"];
			$customerType->updated_by = $user->login;
			$customerType->updated_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$customerType->save();
	        break;
		default:
			exit;
					
	}
	
function getList(){
	$customerTypes = CustomerType::findAll();
	$arr;
	foreach($customerTypes as $customerType){
		$arr[] = $customerType;
	}

	echo $json_response = json_encode($arr);
}

function getOne($id){
	$customerType = CustomerType::findById($id);
	
	echo $json_response = json_encode($customerType);
}