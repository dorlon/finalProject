<!-- מודל התחברות -->
<!-- Modal -->
<div
  class="modal fade"
  id="exampleModal-conection"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">התחברות</h5>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label">אימייל</label>
            <input
              type="email"
              class="form-control"
              id="email_con"
            />
            <div id="emailHelp" class="form-text">
              We'll never share your email with anyone else.
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">סיסמא</label>
            <input
              type="password"
              class="form-control"
              id="password_con"
            />
          </div>
          <button type="button" class="btn btn-primary float-left" id="login">
            התחבר
          </button>
        </form>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary float-left"
          data-bs-dismiss="modal"
        >
          סגור
        </button>
      </div>
    </div>
  </div>
</div>

<!-- מודל הרשמה -->
<!-- Modal -->
<div
  class="modal fade"
  id="exampleModal-sign"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">הירשם</h5>
      </div>
      <div class="modal-body">
        <div>
          <form class="text-right">
            <div class="mb-3">
                <label class="form-label">שם משתמש</label>
                <input
                  type="text"
                  class="form-control"
                  id="full_name"
                  aria-describedby="name"
                />
              </div>

              <div class="mb-3">
                <label class="form-label">אימייל</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  aria-describedby="emailHelp"
                />
                <div id="emailHelp" class="form-text">
                  לא נגלה את פרטיך לאף אחד
                </div>
              </div>
              <div class="mb-3">
                <label
                  for="exampleInputPassword"
                  class="form-label"
                  >סיסמא</label
                >
                <input
                  type="password"
                  class="form-control"
                  id="password"
                />
              </div>
              <div class="mb-3">
                <label
                  for="exampleInputConfirmPassword"
                  class="form-label"
                  >אימות סיסמא</label
                >
                <input
                  type="password"
                  class="form-control"
                  id="verify_password"
                />
              </div>
              <div class="mb-3">
                <label for="exampleInputMobile" class="form-label"
                  >מספר להתקשרות</label
                >
                <input
                  type="text"
                  class="form-control"
                  id="mobile"
                />
              </div>
              <div class="mb-3">
                <label for="exampleInputCity" class="form-label"
                  >עיר</label
                >
                <input
                  type="text"
                  class="form-control"
                  id="city"
                />
              </div>

              <div class="mb-3">
                <label for="exampleInputStreet" class="form-label"
                  >רחוב</label
                >
                <input
                  type="text"
                  class="form-control"
                  id="street"
                />
              </div>

              <div class="mb-3">
                <label for="exampleInputNumberOfHouse" class="form-label"
                  >מספר בית</label
                >
                <input
                  type="number"
                  class="form-control"
                  id="number_house"
                />
              </div>

              <button type="button" id="register" class="btn btn-primary float-left">
                אישור
              </button>
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary float-left"
          data-bs-dismiss="modal"
        >
          סגור
        </button>
      </div>
    </div>
  </div>
</div>