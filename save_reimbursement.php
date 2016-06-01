<?php
	require_once("support/config.php");
	
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
		
		$errors="";

	#VALIDATE DATES

		$val_date=$con->myQuery("SELECT
									DATE_FORMAT(dv.cut_off_start,'%m-%d') AS trans_date_start,
									DATE_FORMAT(dv.cut_off_end,'%m-%d') AS trans_date_end
								FROM date_validations dv");
		
 







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
				$noAttachments=$inputs['countFiles'];
				unset($inputs['save']);
				unset($inputs['countFiles']);
				
				if ($post_status=='save') 
				{
					if (empty($files[0]['tmp_name'])) 
					{
						Alert("Unable to save. Attachment is required.","danger");
						redirect("create_reimbursement.php");
						die();						
					}
				}


				$params=array(
					'payee'=>$inputs['payee'],
					'or_number'=>$inputs['or_number'],
					'invoice_number'=>$inputs['invoice_number'],
					'expense_type'=>$inputs['expense_type'],
					'description'=>$inputs['description'],
					'userid'=>$userid,
					'transaction_date'=>$inputs['transaction_date'],
					'amount'=>$inputs['amount']
					);

				switch($post_status)
				{
					case 'save':
						//$status='For Audit';
						//$filed_date='CURDATE()';
						$con->myQuery("INSERT INTO reimbursements
						(payee, or_number, invoice_number, goods_services, description, user_id, transaction_date, file_date, status, amount)
						VALUES
						(:payee, :or_number, :invoice_number, :expense_type, :description, :userid, :transaction_date, CURDATE(), 'For Audit', :amount)", $params);
					break;

					case 'draft':
						//$status='Draft';
						//$filed_date='0000-00-00';
						$con->myQuery("INSERT INTO reimbursements
						(payee, or_number, invoice_number, goods_services, description, user_id, transaction_date, file_date, status, amount)
						VALUES
						(:payee, :or_number, :invoice_number, :expense_type, :description, :userid, :transaction_date, '0000-00-00', 'Draft', :amount)", $params);
					break;
				}
				//var_dump($inputs);
				//echo "<br>".$filed_date."<br>".$userid."<br>".$status;
				//die();
				
				$reimbursement_id=$con->lastInsertId();
	
				//die();

				foreach ($files as $key => $attachment)
				{
					if(0 == filesize($attachment['tmp_name']))
					{
					
						var_dump($_FILES['file']['tmp_name']);
						//die();
						/*if ($post_status=='save') {
							Alert("Attachment is required","danger");
							redirect("create_reimbursement.php?id=".$inputs['id']);
							die();
						}*/
					}
					else
					{	
						$allowed =  array('jpg', 'png', 'jpeg', 'pdf', 'JPG','PNG','JPEG','PDF');
						$filename = $attachment['name'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						if(!in_array($ext,$allowed) ) 
						{	
    						Alert("Invalid file type.","danger");
    						redirect("create_reimbursement.php");
    						die;
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
						Alert("Save successful","success");
						redirect("reimbursements_all.php");
					break;

					case 'draft':
						record_movement($reimbursement_id,"Created Draft","");
						Alert("Save successful","success");
						redirect("create_reimbursement.php?id=".$reimbursement_id);
					break;
				}
				//$testing = error_reporting(E_ALL);
				//Alert("Save successful","success");
				//redirect("create_reimbursement.php?id=".$reimbursement_id);
				die;
			}
			else
			
#FROM DRAFT REIMBURSEMENT FORM
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

				$check_file=$con->myQuery("SELECT id FROM files WHERE reimbursement_id=?",array($inputs['id']))->fetchAll(PDO::FETCH_ASSOC);
				
				if ($post_status=='save') 
				{
					if (empty($check_file) && empty($files[0]['tmp_name'])) 
					{
						Alert("Unable to save. Attachment is required.","danger");
						redirect("create_reimbursement.php?id=".$inputs['id']);
						die();						
					}
				}
				//var_dump($check_file);
				//echo "<br>";
				//var_dump($files[0]);
				//die();	

				switch($post_status)
				{
					case 'save':
						//$status='For Audit';
						//$filed_date='NOW()';
						$con->myQuery("UPDATE reimbursements
							SET
							payee=:payee,
							or_number=:or_number,
							invoice_number=:invoice_number,
							goods_services=:expense_type,
							description=:description,
							user_id='$userid',
							transaction_date=:transaction_date,
							file_date=CURDATE(),
							status='For Audit',
							amount=:amount
							WHERE
							id=:id", $inputs);
					break;

					case 'draft':
						//$status='Draft';
						//$filed_date='0000-00-00';
						$con->myQuery("UPDATE reimbursements
							SET
							payee=:payee,
							or_number=:or_number,
							invoice_number=:invoice_number,
							goods_services=:expense_type,
							description=:description,
							user_id='$userid',
							transaction_date=:transaction_date,
							file_date='0000-00-00',
							status='Draft',
							amount=:amount
							WHERE
							id=:id", $inputs);
					break;
				}
				
				$reimbursement_id=$inputs['id'];

				foreach ($files as $key => $attachment)
				{
					if(0 == filesize($attachment['tmp_name']))
					{
						//DAPAT WALA NA TONG VALIDATION NA TO, PERO DAPAT MACHECHECK KUNG MAY FILES NA NAKASAMA
					}
					else
					{	
						$allowed =  array('jpg', 'png', 'jpeg', 'pdf', 'JPG','PNG','JPEG','PDF');
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

						if ($post_status=="save") 
						{
							if (empty($file_name) && $file_name=null) 
							{
									echo "no";
									die();
							}	
						}

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
						Alert("Save successful","success");
						redirect("reimbursements_all.php");
					break;

					case 'draft':
						record_movement($reimbursement_id,"Modified Draft","");
						Alert("Save successful","success");
						redirect("create_reimbursement.php?id=".$reimbursement_id);	
					break;
				}
			//Alert("Save successful","success");
			//redirect("reimbursements_all.php");
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