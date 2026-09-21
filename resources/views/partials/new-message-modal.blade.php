  <!-- ===================== New message dialog ===================== -->
  <div class="modal fade" role="dialog" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" action="#" method="post">
        <div class="modal-header">
          <h2 class="modal-title h5" id="newMessageLabel">New message</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="messageTo">To</label>
            <input type="text" class="form-control" id="messageTo" name="to" list="friendNames"
                   placeholder="Type a name" autocomplete="off" required>
            <datalist id="friendNames">
              <option value="Ali Nahleh"></option>
              <option value="Maysam Chaalan"></option>
              <option value="Elias Al Omar"></option>
              <option value="Haitham Ismail"></option>
              <option value="Ali Ayoub"></option>
              <option value="Elie Amin"></option>
              <option value="Baqer Alayyan"></option>
            </datalist>
          </div>

          <div>
            <label class="form-label" for="messageBody">Message</label>
            <textarea class="form-control" id="messageBody" name="body" rows="3" placeholder="Say hello…" required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">Send</button>
        </div>
      </form>
    </div>
  </div>
