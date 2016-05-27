<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    
	
	if(!empty($_POST))
	{
		$inputs=$_POST;
		
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
				die;
			}
			
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
				$noAttachments=$inputs['countFiles'];
				unset($inputs['save']);
				unset($inputs['countFiles']);
				
				switch($post_status)
				{
					case 'save':
						$status='For Audit';
						$filed_date='NOW()';
					break;

					case 'draft':
						$status='Draft';
						$filed_date='null';
					break;
				}

				$con->myQuery("INSERT into reimbursements(payee, or_number, invoice_number, goods_services, description, user_id, transaction_date, file_date, status, amount)
					VALUES
					(:payee, :or_number, :invoice_number, :expense_type, :description, '$userid', :transaction_date, '$filed_date', '$status', :amount)", $inputs);
				$reimbursement_id=$con->lastInsertId();

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
				switch($post_status)
				{
					case 'save':
						record_movement($reimbursement_id,"Submitted For Audit","");
					break;

					case 'draft':
						record_movement($reimbursement_id,"Created Draft","");
					break;
				}
				//$testing = error_reporting(E_ALL);
				Alert("Save successful","success");
				redirect("create_reimbursement.php?id=".$reimbursement_id);
				die;
			}
			else
			{
				$inputs=$_POST;
				$files=reArrayFiles($_FILES['file']);
						
				//unset($inputs['id']);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				
				$i=0;
				$post_status=$inputs['save'];
				$noAttachments=$inputs['countFiles'];
				unset($inputs['save']);
				unset($inputs['countFiles']);
				
				switch($post_status)
				{
						case 'save':
							$status='For Audit';
							$filed_date='NOW()';
						break;

						case 'draft':
							$status='Draft';
							$filed_date='null';
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
				
				$reimbursement_id=$inputs['id'];
				
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
			switch($post_status)
				{
						case 'save':
							record_movement($reimbursement_id,"Submitted For Audit","");
						break;

						case 'draft':
							record_movement($reimbursement_id,"Modified Draft","");
						break;
				}
			Alert("Save successful","success");
			redirect("create_reimbursement.php?id=".$inputs['id']);
			die();
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