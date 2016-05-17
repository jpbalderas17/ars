<div class="modal" id='modal_return'>
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action='return_request.php'>
          <input type='hidden' name='id' id='return_id' value=''>
          <input type='hidden' name='return_page' value='<?php echo !(empty($return_page))?$return_page:"";?>'>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Return Reimbursement</h4>
          </div>
          <div class="modal-body" >
            <div class='form-group'>
            <label class='pull-left'>Reason for returning:</label>
              <textarea name='reason' required="" class='form-control' style='resize: none' rows='4' required=""></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-brand btn-flat">Return Request</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <script type="text/javascript">
    function return_request(id){
            $('#modal_return').modal('show');
            $('#return_id').val(id);
        }
  </script>