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
	if(!empty($_POST))
	{
		//Validate form inputs
		$inputs=$_POST;
		
				// var_dump($inputs);
				// die;
		$errors="";
		
		if (empty($inputs['description']))
		{
			
		}


		if($errors!="")
		{

			Alert("Please fill in the following fields: <br/>".$errors,"danger");
			if(empty($inputs['id']))
			{
				redirect("create_reimbursement.php");
			}
			
			die;
		}
		else
		{
			//IF id exists update ELSE Insert
			if(empty($inputs['id']))
			{
				//Insert
				$inputs=$_POST;
				$files=reArrayFiles($_FILES['file']);
				
				unset($inputs['id']);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				
				$i=0;
				$post_status=$inputs['save'];
				unset($inputs['save']);
				$reimbursement_id=$con->lastInsertId();
				switch($post_status)
				{
					case 'save':
						$status='For Audit';
						$filed_date='NOW()';
						record_movement($reimbursement_id,"Submitted For Audit","");
					break;

					case 'draft':
						$status='Draft';
						$filed_date='null';
						record_movement($reimbursement_id,"Created Draft","");
					break;
				}

				$con->myQuery("INSERT into reimbursements(payee, or_number, invoice_number, goods_services, description, user_id, transaction_date, file_date, status, amount)
					VALUES
					(:payee, :or_number, :invoice_number, :expense_type, :description, '$userid', :transaction_date, '$filed_date', '$status', :amount)", $inputs);
				
				
				
				foreach ($files as $key => $attachment)
				{
					if(0 == filesize($attachment['tmp_name']))
					{
					
						var_dump($_FILES['file']['tmp_name']);
						die();
					}
					else
					{	
						$allowed =  array('jpg', 'png', 'jpeg', 'pdf');
						$filename = $attachment['name'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						if(!in_array($ext,$allowed) ) 
						{	
    						Alert("Invalid file type.","danger");
    						redirect("create_reimbursement.php");
    					
						}
						$i++;
						$file_id=$_POST['id']. "_" . "Quotation" . "_" . (new \DateTime())->format('Y-m-d-H-i-s').$i;

						$name=$file_id.getFileExtension($attachment['name']);

						move_uploaded_file($attachment['tmp_name'],"Attachments/".$name);
					
						$file_location="Attachments/".$name;
						$file_name=$attachment['name'];

						$con->myQuery("INSERT into files
						(reimbursement_id, date_added, file_name, file_location)
						VALUES
						('$reimbursement_id', NOW(), '$file_name', '$file_location')", $inputs);
					
					}
				
				}
				//$testing = error_reporting(E_ALL);
				Alert("Save successful","success");
			
			}
			else
			{
				$inputs=$_POST;
				$files=reArrayFiles($_FILES['file']);
						
				//unset($inputs['id']);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				
				$i=0;
				$post_status=$inputs['save'];
				unset($inputs['save']);
				$reimbursement_id=$con->lastInsertId();
				switch($post_status)
				{
						case 'save':
							$status='For Audit';
							$filed_date='NOW()';
							record_movement($reimbursement_id,"Submitted For Audit","");
						break;

						case 'draft':
							$status='Draft';
							$filed_date='null';
							record_movement($reimbursement_id,"Modified Draft","");
						break;
				}

				$con->myQuery("UPDATE reimbursements
					SET
					payee=:payee,
					or_number=:or_number,
					invoice_number=:invoice_number,
					goods_services=:expense_type,
					description=:description,
					user_id='$userid',
					transaction_date=:transaction_date,
					file_date='$filed_date',
					status='$status',
					amount=:amount
					WHERE
					id=:id", $inputs);
				//var_dump($inputs);
					//die();

				
				foreach ($files as $key => $attachment)
				{
					if(0 == filesize($attachment['tmp_name']))
					{
						//DAPAT WALA NA TONG VALIDATION NA TO, PERO DAPAT MACHECHECK KUNG MAY FILES NA NAKASAMA
					}
					else
					{	
						$allowed =  array('jpg', 'png', 'jpeg', 'pdf');
						$filename = $attachment['name'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);
						if(!in_array($ext,$allowed) ) 
						{	
						
    						Alert("Invalid file type.","danger");
    						redirect("create_reimbursement.php");
    					
						}
						$i++;
						$file_id=$_POST['id']. "_" . "Quotation" . "_" . (new \DateTime())->format('Y-m-d-H-i-s').$i;

						$name=$file_id.getFileExtension($attachment['name']);
					
						move_uploaded_file($attachment['tmp_name'],"Attachments/".$name);
					
						$file_location="Attachments/".$name;
						$file_name=$attachment['name'];

						$con->myQuery("INSERT into files
						(reimbursement_id, date_added, file_name, file_location)
						VALUES
						('$reimbursement_id', NOW(), '$file_name', '$file_location')", $inputs);
					
				
					}
				}
			Alert("Save successful","success");
			redirect("create_reimbursement.php");
			}
		
		}
	}
	else
	{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>