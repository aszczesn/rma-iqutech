<?php 
/**
 * Albert Szczesny
 * @copyright 2014
 * dictionaries/diy_part/index.php
 */
 
require_once('../../../../includes/initialize.php');

$session = new WidgetSession();
$session->Impress();
			$session->Login("albert", "lade978j=");
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
        
	    case "update":	 
			$partInventory = PartInventory::findById($_POST["id"]);
			$partInventory->remarks = $_POST["remarks"];

			$partInventory->save();
	        break;
		default:
			//getList();
			exit;
					
	}
	
function getList(){
	$partInventorys = PartInventory::findAll();
	$arr=array();
	foreach($partInventorys as $partInventory){
		$parentPart = Part::findById($partInventory->parentPartId);
		$part = Part::findById($partInventory->partId);
		
		$partInventory->parentPartNumber = $parentPart->partNumber;
		$partInventory->partNumber = $part->partNumber;
		$partInventory->description = $part->description;
		$partInventory->stock_d = $partInventory->stock_a - $partInventory->stock_b - $partInventory->stock_c;
		$partInventory->stock_e = $partInventory->stock_b + $partInventory->stock_c;
		$arr[] = $partInventory;
	}

	echo $json_response = json_encode($arr);
}

function getOne($id){
	$partInventory = Part::findById($id);
	echo $json_response = json_encode($partInventory);
}