<section class="content">
	<div class="container-fluid">
		<div class="block-header">
      <h2><?php echo $block_header ?></h2>
		</div>

    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">playlist_add_check</i>
                </div>
                <div class="content">
                    <div class="text">TOTAL INVENTORI SAMPAI BULAN INI</div>
                    <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"><?php echo number_format( $sale_sum )?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">playlist_add_check</i>
                </div>
                <div class="content">
                    <div class="text">TOTAL PENJUALAN SAMPAI BULAN INI</div>
                    <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"><?php echo number_format( $inventory_sum )?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="header">
            <h2>Data 5 Bulan Terakhir</h2>
        </div>
        <div class="body"><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"></iframe>
            <canvas id="line_chart" height="347" width="694" style="display: block; width: 694px; height: 347px;"></canvas>
        </div>
    </div>
	</div>
</section>
<textarea style="display:none" id="arr_x" >
        <?php echo json_encode( $arr_x) ; ?>
</textarea>
<textarea style="display:none" id="sales" >
        <?php echo json_encode( $sales) ; ?>
</textarea>

<textarea style="display:none" id="inventories" >
        <?php echo json_encode( $inventories) ; ?>
</textarea>

<script src="<?php echo base_url('assets/')?>vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('assets/')?>js/admin/pages/charts/dashboard_chart.js"></script>
<script src="<?php echo base_url('assets/')?>vendor/chartjs/Chart.bundle.js"></script>

