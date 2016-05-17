<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    
	// if(!AllowUser(array(1,2))){
	// 	redirect("index.php");
	// }
	 //var_dump($_POST);
		//		 die;
	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		
				// var_dump($inputs);
				// die;
		$errors="";
		
		if (empty($inputs['description'])){
			
		}


		if($errors!=""){

			Alert("Please fill in the following fields: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("create_reimbursement.php");
			}
			
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				$inputs=$_POST;
				
				
				//$inputs['name']=$_POST['name'];
				unset($inputs['id']);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				//$opp_id=$_POST['opportunity_name'];
				//$item=$_POST['id'];
				//$page="quotes";
				foreach($_FILES['file']['tmp_name'] as $attachment){
				if(0 == filesize($attachment)){
					$name="Default.jpg";
					var_dump($_FILES['file']['tmp_name']);
					die();
				}
				else
				{	
					$allowed =  array('jpg', 'png', 'jpeg', 'pdf');
					$filename = $_FILES['file']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(!in_array($ext,$allowed) ) 
					{
    					Alert("Invalid file type.","danger");
    					redirect("create_reimbursement.php");
    					die();
					}

					$file_id=$_POST['id']. "_" . "Quotation" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');

					$name=$file_id.getFileExtension($_FILES['file']['name']);
					//$tmp_name = $_FILES['file']['tmp_name'];
				
					//move_uploaded_file($_FILES['file']['tmp_name'],"Attachments/".$name);
					$reimbursement_id=$con->lastInsertId();
					$file_location="Attachments/".$name;
					$con->myQuery("insert into files
					(reimbursement_id, date_added, file_location, is_deleted)
					VALUES
					('$reimbursement_id', NOW(), '$file_location')", $inputs);
					
				}
				}
				$status="For Audit";
				$con->myQuery("INSERT into reimbursements(payee, or_number, invoice_number, goods_services, description, user_id, transaction_date, file_date, status, amount)
					VALUES
					(:payee, :or_number, :invoice_number, :expense_type, :description, '$userid', :transaction_date, NOW(), '$status', :amount)", $inputs);
					//var_dump($inputs); 
					//die();

	
				$testing = error_reporting(E_ALL);
				Alert("Save successful","success");
				

			}
			else{
				//Update
				//date_default_timezone_set('Asia/Manila');
				//$now = new DateTime();
				//$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs=$_POST;
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$opp_id=$_POST['opportunity_name'];
				$item=$_POST['id'];
				$page="quotes";

				if(0 == filesize($_FILES['file']['tmp_name'])){
					
					
					$con->myQuery("UPDATE quotes SET opportunity_name=:opportunity_name, description=:description, title=:title, date_uploaded=NOW()WHERE id=:id",$inputs);
					$notes="Updated quote id ".$item." details."; 
					$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, page, item, action_date) VALUES ('$opp_id', '$userid', '$notes', '$page', '$item', NOW())", $inputs);
					
					Alert("Update successful","success");
				}
				else{

					$allowed =  array('doc', 'docx', 'xls', 'xlsx', 'xlsx');
					$filename = $_FILES['file']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(!in_array($ext,$allowed) ) 
					{
    					Alert("Invalid file type.","danger");
    					redirect("quotes.php");
    					die();
					}

				$file_id=$_POST['opportunity_name']. "_" . "Quotation" . "_" . (new \DateTime())->format('Y-m-d-H-i-s');

				$name=$file_id.getFileExtension($_FILES['file']['name']);
				//$tmp_name = $_FILES['file']['tmp_name'];
				
				move_uploaded_file($_FILES['file']['tmp_name'],"uploads/Documents/".$name);

				$con->myQuery("UPDATE quotes SET opportunity_name=:opportunity_name, description=:description, title=:title, date_uploaded=NOW(), document='$name' WHERE id=:id",$inputs);
				$notes="Updated quote id ".$_POST['id']." file."; 
				$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, action_date) VALUES ('$opp_id', '$userid', '$notes', NOW())", $inputs);
				Alert("Update successful","success");
				}
					
				
			}

			
			redirect("create_reimbursement.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>