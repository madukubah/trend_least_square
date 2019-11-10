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
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <h2>
                              <?php echo strtoupper($header)?>
                              <small><?php echo $sub_header ?></small>
                          </h2>
                        </div>
                        <!-- search form -->
                        <div class="col-md-6">
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
                <div class="body" id="content" >
                    <!--  -->
                    <form action="<?php echo site_url( $current_page."trend_projection" ) ?>" method="GET">
                        <?php echo ( isset( $contents )  ) ? $contents : '' ;  ?>
                            <!--  -->
                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <label for="" class="control-label">Ramalan Bulan</label>
                                                <div id="end_month_2" > 
                                                    <select name="end_month_2"  type="select" placeholder="Ramalan Bulan" class="form-control" tabindex="-98">
                                                        <option value="1">Januari</option>
                                                        <option value="2" selected="selected">Februari</option>
                                                        <option value="3">Maret</option>
                                                        <option value="4">April</option>
                                                        <option value="5">Mei</option>
                                                        <option value="6">Juni</option>
                                                        <option value="7">Juli</option>
                                                        <option value="8">Agustus</option>
                                                        <option value="9">September</option>
                                                        <option value="10">Oktober</option>
                                                        <option value="11">November</option>
                                                        <option value="12">Desember</option>
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <label for="" class="control-label">Ramalan Tahun</label>
                                                <select name="end_year_2" id="end_year_2" type="select" placeholder="Ramalan Tahun" class="form-control" tabindex="-98">
                                                    <option value="2030">2030</option>
                                                    <option value="2029">2029</option>
                                                    <option value="2028">2028</option>
                                                    <option value="2027">2027</option>
                                                    <option value="2026">2026</option>
                                                    <option value="2025">2025</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2023">2023</option>
                                                    <option value="2022">2022</option>
                                                    <option value="2021">2021</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2019" selected >2019</option>
                                                    <option value="2018">2018</option>
                                                    <option value="2017">2017</option>
                                                    <option value="2016">2016</option>
                                                </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <button class="btn btn-bold btn-primary btn-sm " style="margin-left: 5px;" type="submit">
                                Trend Projection
                            </button>

                            <?php echo form_close()  ?>
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
<textarea style="display:none" id="months" >
        <?php echo json_encode($months) ; ?>
</textarea>
<script type="text/javascript">
$(document).ready(function(){
  // var messages = [];
  // var trips = 0;
  // var quantity_leftovers = $("#quantity_leftovers").val().trim();
  // var cars = JSON.parse( $("#cars").val().trim() ) ;
  var months = JSON.parse( $("#months").val().trim() ) ;
  $("#content").on('change','select[name="end_month"]',function(){
        // console.log( months );
        changeMonthTreshold( $(this).val() );
  });

  $("#content").on('change','select[name="end_year"]',function(){
        console.log( $(this).val() );
        // changeMonthTreshold( $(this).val() );
  });

  function changeMonthTreshold( month = 1 )
  {
      var end_month_2 = '<select name="end_month_2"  type="select" placeholder="Ramalan Bulan" class="form-control" tabindex="-98">';

      for(var i=month; i<= 12 ; i++)
      {
        end_month_2 += '<option value="'+ i +'">'+months[i]+'</option>';
      }

      end_month_2 += '</select>';

      $("#end_month_2").html( end_month_2 );
  }
  
});
</script>