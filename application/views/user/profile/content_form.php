<section class="content">
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
        <div class="row clearfix">
          <!-- header -->
          <div class=" col-md-12 ">
              <div class="card">
                  <div class="header">
                      <div class="row clearfix">
                          <div class="col-md-12">
                                <!-- alert  -->
                                <?php
                                    echo $alert;
                                ?>
                                <!-- alert  -->
                          </div>
                      </div>
                      <!--  -->
                      <div class="row clearfix" >
                        <div class="col-md-6">
                          <h2>
                              <?php echo strtoupper($header)?>
                          </h2>
                        </div>
                      </div>
                      <!--  -->
                  </div>
              </div>
          </div>
          <!--  -->
          <div class=" col-md-8 ">
              <div class="card">
                  <div class="body">
                      <!--  -->
                      <?php echo form_open();  ?>
                      <?php echo ( isset( $contents )  ) ? $contents : '' ;  ?>
                      
                      <button class="btn btn-bold btn-success btn-sm " style="margin-left: 5px;" type="submit" >
                        Simpan
                      </button>

                      <?php echo form_close()  ?>
                      <!--  -->
                  </div>
              </div>
          </div>
          <!-- photo -->
          <div class=" col-md-4 ">
              <div class="row clearfix">
                  <div class=" col-md-12 ">
                        <div class="card">
                            <div class="body">
                                <img class="img-responsive thumbnail" src="<?php echo base_url('uploads/users_photo/').$user->image?>">

                                <?php echo ( isset( $edit_photo )  ) ? $edit_photo : '' ;  ?>
                                
                            </div>
                        </div>
                  </div>
              </div>
          </div>
          <!--  -->
      </div>
</section>
