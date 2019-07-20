<section class="container g-py-100">
      <div class="row justify-content-center">
        <div class="col-sm-8 col-lg-5">
          <div class="g-brd-around g-brd-gray-light-v4 rounded g-py-40 g-px-30">
            <header class="text-center mb-4">
              <h2 class="h2 g-color-black g-font-weight-600">Login</h2>
            </header>
            <?php
              if($this->session->flashdata('alert')){
                echo $this->session->flashdata('alert');
              }?>
            <!-- Form -->
            <?php echo form_open("");?>
              <div class="mb-4">
                <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Email:</label>
                <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="email" name="identity" placeholder="johndoe@gmail.com">
              </div>

              <div class="g-mb-35">
                <div class="row justify-content-between">
                  <div class="col align-self-center">
                    <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Password:</label>
                  </div>
                  <div class="col align-self-center text-right">
                    <a class="d-inline-block g-font-size-12 mb-2" href="#">Forgot password?</a>
                  </div>
                </div>
                <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15 mb-3" name="user_password" type="password" placeholder="Password">
                <div class="row justify-content-between">
                  <div class="col-8 align-self-center">

                  </div>
                  <div class="col-4 align-self-center text-right">
                    <button class="btn btn-md u-btn-primary rounded g-py-13 g-px-25" type="submit">Login</button>
                  </div>
                </div>
              </div>
            </form>
            <!-- End Form -->

            <footer class="text-center">
              <p class="g-color-gray-dark-v5 g-font-size-13 mb-0">Tidak punya akun? Register<a class="g-font-weight-600" href="<?php echo site_url('auth/register')?>"> Register</a>
              </p>
            </footer>
          </div>
        </div>
      </div>
    </section>
