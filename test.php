<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	
	makeHead();
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class='page-header text-center text-brand'>
            Check it
          </h1>
        </section>
        <div class='col-md-12'>
            <?php
                Alert();
            ?>
        </div>
        <!-- Main content -->
        <section class="content">
        	<?php
        		if(!empty($_POST)){
					var_dump($_POST);
					/*	
							^

							Here is the data. 
							Validate? your choice.
							Loop and save dat shit.
					*/
				}
        	?>
           <!-- Button trigger modal -->
			<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
			  Launch demo modal
			</button>

			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Modal ng mother mo</h4>
			      </div>
			      <div class="modal-body">
			      	<form onsubmit="return false" id='modal_form'>
			      		<div class="form-group">
			      			<label>THIS ALBEL</label>
			      			<select class='select2 form-control' name='select_1'>
			      				<option value='1'>This is 1</option>
			      				<option value='2'>This is 2</option>
			      				<option value='3'>This is 3</option>
			      				<option value='4'>This is 4</option>
			      			</select>
			      		</div>
			      		<div class="form-group">
			      			<label>THIS Text</label>
			      			<input type='text' name='text_1' class='form-control'>
			      		</div>
			      	</form>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-primary" onclick="AddToTable()">Add</button>
			      </div>
			    </div>
			  </div>
			</div>
			<form method="post">
			<button type='submit' class='pull-right btn btn-brand btn-flat'>SAVE THEM DATA BRuH</button>
			<table class='table table-bordered '>
				<thead>
					<tr>
						<td>Select</td>
						<td>Label</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody id='table_container'>
					
				</tbody>
			</table>
			</form>
        </section><!-- /.content -->
  </div>
<script type="text/javascript">
	function AddToTable() {
		select_1_val=$("select[name='select_1']").val();
		select_1_text=$("select[name='select_1'] :selected").text()
		text_1=$("input[name='text_1']").val();

		/*
			See those group of letters up there^?
			They just get the M@#$%^&*# Values of the M%*!@#*!@# Form on the modal.
			More inputs?
			Just add them there.

			Also YOU should validate that shit.
			Make sure the data is not already int the table.
		*/


		input="<input type='hidden' name='select_1[]' value='"+select_1_val+"'> <input type='hidden' name='text_1[]' value='"+text_1+"'>";
		/*
			SEE THIS SHIT RIGHT HERE ^?
			Thats what gets sent for saving. 
			See the M*@#$%^&* square brackets? They allow the data to be passed as an array. That shit is important.
		*/

		$("#table_container").append("<tr>"+input+"<td>"+select_1_text+"</td><td>"+text_1+"</td><td><button type='button' onclick='removeRow(this)' class='btn btn-danger'>X</button></td></tr>");
		/*
			UPTOP ^?
			This is what adds the data to the table.

			that in the end? That deletes the whole row.
		*/

		$('#myModal').modal("hide");
		/*
			CLose that MOdal Bruh
		*/
		$("#modal_form")[0].reset()
		/*
			RESET The form data.
			Forget the past to start with the future.?
		*/
	}

	function removeRow(del_button) {
		// body...
		if(confirm('Remove this row?')){
		$(del_button).parent().parent().remove();
			
		}
		return false;
		/*
				^
			Get theat row out of here.;
		*/
	}
</script>
<?php
    Modal();
	makeFoot();
?>