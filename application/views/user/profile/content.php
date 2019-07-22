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
                      <?php echo ( isset( $contents )  ) ? $contents : '' ;  ?>
                      <!--  -->
                      <!--  -->
                      <?php echo ( isset( $pagination_links )  ) ? $pagination_links : '' ;  ?>
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
                            </div>
                        </div>
                  </div>
                  <div class=" col-md-12 ">
                        <div class="card">
                            <div class="body">
                                <a href="<?php echo site_url('user/profile/edit') ?>" class="btn btn-block btn-md btn-primary waves-effect">Edit</a>
                                <br>
                            </div> 
                        </div>
                  </div>
              </div>
          </div>
          <!--  -->
      </div>
</section>
