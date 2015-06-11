<?php 
/**
 * Albert Szczesny
 * @copyright 2014
 * misc/Rma/index.php
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
$user = $session->getUserObject();
switch($action){
	    case "show": 
	        getList();
	        break;
	    case "getOne":
			if(isset($_POST["id"]))
				$id = $_POST["id"];
	        getOne($id);
	    break;	
		case "insert":
			$rmano = new Rma();
			$rmano->rmano = $_POST["rmano"];
			$rmano->supplierId = $_POST["supplierId"];
			$rmano->issue_date = $_POST["issue_date"];
			$rmano->created_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$rmano->created_by = $user->login;
			$rmano->save();
			
			break;
	        
	    case "delete":
			$rmano = Rma::findById($_POST["id"]);
	        $rmano->delete();
	        break;
	        
	    case "update":	 
			$rmano = Rma::findById($_POST["id"]);
			$rmano->rmano = $_POST["rmano"];
			$rmano->supplierId = $_POST["supplierId"];
			$rmano->issue_date = $_POST["issue_date"];
			$rmano->updated_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$rmano->updated_by = $user->login;
			$rmano->save();
			
	        break;
		default:
			exit;
					
	}
	
function getList(){
	$rmanos = Rma::findAll();
	$arr;
	foreach($rmanos as $rmano){
		$supplier = Customer::findById($rmano->supplierId);
		$rmano->supplierName = $supplier->name;
		$arr[] = $rmano;
	}

	echo $json_response = json_encode($arr);
}

function getOne($id){
	$rmano = Rma::findById($id);
	echo $json_response = json_encode($rmano);
}