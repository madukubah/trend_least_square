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
                      <h3>Hasil</h3>
                      <div  class="table-responsive ">
                        <table class="table table-striped table-bordered table-hover  ">
                          <tr>
                            <td><b>x</b></td>
                            <td>x</td>
                            <td><?php echo $result->next_x ?></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td><b>a</b></td>
                            <td> &Sigma;y / n </td>
                            <td><?php echo $result->_y." / ". $_n ?></td>
                            <td><?php echo $result->_y / $_n ?></td>
                          </tr>
                          <tr>
                            <td><b>b</b></td>
                            <td> &Sigma;xy / &Sigma;x^2 </td>
                            <td><?php echo $result->_xy ." / ". $result->_xx?></td>
                            <td><?php echo $result->_xy / $result->_xx?></td>
                          </tr>
                          <tr>
                            <td><b>y`</b></td>
                            <td> a + b(x)</td>
                            <td><?php echo ( $result->_y / $_n ) ." + ". ( $result->_xy / $result->_xx ) . " * " . $result->next_x  ?></td>
                            <td><?php echo $_y_accent = ( $result->_y / $_n ) + ( $result->_xy / $result->_xx * $result->next_x ) ?></td>
                          </tr>
                        </table>
                      </div>
                      <h3>Kesimpulan</h3>
                      <h5>
                        Prediksi <?php echo $inference ?> untuk bulan selanjutnya ( <?php echo Util::MONTH[ $next_month_prediction->month ]." ".$next_month_prediction->year ?> ) adalah <?php echo $_y_accent ?>
                      </h5>
                      <br>
                      <div class="card">
                          <div class="header">
                              <h2>CHART</h2>
                          </div>
                          <div class="body"><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"></iframe>
                              <canvas id="line_chart" height="347" width="694" style="display: block; width: 694px; height: 347px;"></canvas>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
	<!-- </div> -->
<textarea style="display:none" id="data_real" >
        <?php echo json_encode( $data_real) ; ?>
</textarea>
<textarea style="display:none" id="data_x" >
        <?php echo json_encode( $data_x) ; ?>
</textarea>

<textarea style="display:none" id="data_prediction" >
        <?php echo json_encode( $data_prediction) ; ?>
</textarea>
</section>

<script src="<?php echo base_url('assets/')?>vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('assets/')?>js/admin/pages/charts/chartjs.js"></script>
<script src="<?php echo base_url('assets/')?>vendor/chartjs/Chart.bundle.js"></script>


