<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
        <!-- alert  -->
        <?php
            echo $alert;
        ?>
        <!-- alert  -->
        <h2>
            LAPORAN 
        </h2>
    </div>
    <!-- Basic Table -->
    <div class="row clearfix">
      <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
          <div class="header bg-green">
            <h2>
              Laporan - Inventori
            </h2>
            <ul class="header-dropdown m-r--5">
              <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
                </a>
                <ul class="dropdown-menu pull-right">
                  <li><a href="javascript:void(0);">Action</a></li>
                  <li><a href="javascript:void(0);">Another action</a></li>
                  <li><a href="javascript:void(0);">Something else here</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <div class="body">
            Mencetak Laporan inventori dari bulan X ke bulan Y
            <hr>

            <form target="blank" action="<?php echo site_url( $current_page."trend_projection" ) ?>" method="GET" >
            <?php echo $inventory ?>
            <?php echo form_submit('submit', 'Cetak', 'class="btn bg-blue btn-lg waves-effect" title="Cetak Data Surat Masuk"');?>
            <?php echo form_close();?>

          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
          <div class="header bg-green">
            <h2>
              Laporan - Penjualan
            </h2>
            <ul class="header-dropdown m-r--5">
              <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
                </a>
                <ul class="dropdown-menu pull-right">
                  <li><a href="javascript:void(0);">Action</a></li>
                  <li><a href="javascript:void(0);">Another action</a></li>
                  <li><a href="javascript:void(0);">Something else here</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <div class="body">
            Mencetak Laporan Penjualan dari bulan X ke bulan Y
            <hr>
            <form target="blank" action="<?php echo site_url( $current_page."trend_projection" ) ?>" method="GET" >
            <?php echo $sale ?>
            <?php echo form_submit('submit', 'Cetak', 'class="btn bg-blue btn-lg waves-effect" title="Cetak Data Surat Keluar"');?>
            <?php echo form_close();?>
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Basic Table -->
  </div>
</section>