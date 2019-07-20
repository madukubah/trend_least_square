<section class="container g-py-100">
  <div class="row justify-content-center">
    <div class="col-sm-10 col-md-9 col-lg-6">
      <div class="g-brd-around g-brd-gray-light-v4 rounded g-py-40 g-px-30">
        <header class="text-center mb-4">
          <h2 class="h2 g-color-black g-font-weight-600">Register</h2>
        </header>
        <?php
          if($this->session->flashdata('alert')){
            echo $this->session->flashdata('alert');
          }?>
        <!-- Form -->
        <?php echo form_open("");?>
          <div class="row">
            <div class="col-xs-12 col-sm-6 mb-4">
              <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">First name:</label>
                <?php echo form_input($first_name);?>
            </div>

            <div class="col-xs-12 col-sm-6 mb-4">
              <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Last name:</label>
                <?php echo form_input($last_name);?>
            </div>
          </div>

          <div class="mb-4">
            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Email:</label>
            <?php echo form_input($email);?>
          </div>
          <div class="mb-4">
            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Phone:</label>
            <?php echo form_input($phone);?>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6 mb-4">
              <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Password:</label>
              <?php echo form_input($password);?>

            </div>

            <div class="col-xs-12 col-sm-6 mb-4">
              <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Re-Password:</label>
              <?php echo form_input($password_confirm);?>

            </div>
          </div>

          <div class="row justify-content-between mb-5">
            <div class="col-8 align-self-center">
              <!--
              <label class="form-check-inline u-check g-color-gray-dark-v5 g-font-size-13 g-pl-25">
                <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" type="checkbox">
                <div class="u-check-icon-checkbox-v6 g-absolute-centered--y g-left-0">
                  <i class="fa" data-check-icon=""></i>
                </div>
                I accept the <a href="#">Terms and Conditions</a>
              </label>
            -->
            </div>
            <div class="col-4 align-self-center text-right">
              <button class="btn btn-md u-btn-primary rounded g-py-13 g-px-25" type="submit">Register</button>
            </div>
          </div>
        </form>
        <!-- End Form -->

        <footer class="text-center">
          <p class="g-color-gray-dark-v5 g-font-size-13 mb-0">Already have an account? <a class="g-font-weight-600" href="page-login-1.html">signin</a>
          </p>
        </footer>
      </div>
    </div>
  </div>
</section>
