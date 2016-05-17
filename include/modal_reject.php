<div class="modal" id='modal_reject'>
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action='reject_request.php'>
          <input type='hidden' name='id' id='reject_id' value=''>
          <input type='hidden' name='return_page' value='<?php echo !(empty($return_page))?$return_page:"";?>'>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Reject Request</h4>
          </div>
          <div class="modal-body" >
            <div class='form-group'>
            <label class='pull-left'>Reason for Rejection:</label>
              <textarea name='reason' required="" class='form-control' style='resize: none' rows='4' required=""></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-brand btn-flat">Reject</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <script type="text/javascript">
    function reject(id){
            $('#modal_reject').modal('show');
            $('#reject_id').val(id);
        }
  </script>