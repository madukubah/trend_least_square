<section class="content">
	<!-- <div class="container-fluid"> -->
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
        <div class="row clearfix">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                        <div class="col-md-4">
                          <h2>
                              <?php echo strtoupper($header)?>
                              <small><?php echo $sub_header ?></small>
                          </h2>
                        </div>
                        <!-- search form -->
                        <div class="col-md-8">
                          <div class="row clearfix">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-10">
                                    <div class="pull-right">
                                            <?php echo ( isset( $header_button )  ) ? $header_button : '' ;  ?>
                                    </div>
                                <!--  -->
                            </div>

                          </div>
                        </div>
                        <!--  -->
                      </div>
                      <!--  -->
                  </div>
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
      </div>
	<!-- </div> -->
</section>
