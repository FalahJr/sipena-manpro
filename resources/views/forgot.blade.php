<!-- Modal -->
<div id="modal_forgot" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Forgot Password</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
            Please fill in all the data marked with <span style="color:red;">*</span>
            </div>
          {{-- <tr>
            <td>Pilih User</td>
            <td>
              <input type="text" class="form-control form-control-md autocomplete pilihuser" name="pilihuser">
              <input type="hidden" class="form-control form-control-sm id" name="id">
            </td>
          </tr> --}}
          <tr>
            <td>Your Email <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext email" name="email">
            </td>
          </tr>
          <tr>
            <td>Code Forgot <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext code" maxlength="6" name="code">
            </td>
          </tr>
          <tr>
            <td>New Password <span style="color:red;">*</span></td>
            <td>
              <input type="password" class="form-control form-control-sm inputtext password" name="password">
            </td>
          </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" id="simpanforgot" type="button">Process</button>
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
      </div>
      </div>

  </div>
</div>
