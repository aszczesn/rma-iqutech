<?php 
/**
 * Albert Szczesny
 * @copyright 2014
 * misc/customer/index.php
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
			$customer = new Customer();
			$customer->shortname = $_POST["shortname"];
			$customer->name = $_POST["name"];
			$customer->categoryid = $_POST["categoryid"];
			$customer->phoneno = $_POST["phoneno"];
			$customer->email = $_POST["email"];
			$customer->address = $_POST["address"];
			$customer->city = $_POST["city"];
			$customer->zip = $_POST["zip"];
			$customer->country = $_POST["country"];
			$customer->created_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$customer->created_by = $user->login;
			$customer->save();
			break;
	        
	    case "delete":
			$customer = Customer::findById($_POST["id"]);
	        $customer->delete();
	        break;
	        
	    case "update":	 
			$customer = Customer::findById($_POST["id"]);
			$customer->shortname = $_POST["shortname"];
			$customer->name = $_POST["name"];
			$customer->categoryid = $_POST["categoryid"];
			$customer->phoneno = $_POST["phoneno"];
			$customer->address = $_POST["address"];
			$customer->city = $_POST["city"];
			$customer->email = $_POST["email"];
			$customer->zip = $_POST["zip"];
			$customer->country = $_POST["country"];
			$customer->updated_at = strftime("%Y-%m-%d %H:%M:%S", time());
			$customer->updated_by = $user->login;
			$customer->save();
	        break;
		default:
			exit;
					
	}
	
function getList(){
	$customers = Customer::findAll();
	$arr;
	foreach($customers as $customer){
		$customerType = CustomerType::findById($customer->categoryid);
		$customer->categoryName = $customerType->name;
		$arr[] = $customer;
	}

	echo $json_response = json_encode($arr);
}

function getOne($id){
	$customer = Customer::findById($id);
	echo $json_response = json_encode($customer);
}