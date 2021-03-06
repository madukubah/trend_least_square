<?php
    $data = ( isset( $data ) && $data != NULL )? $data : '';
    $data_param = ( $data != '' )? $data->$param : '';
?>
<button class="btn btn-<?php echo $button_color ?> btn-sm" style="margin-left: 5px;" data-toggle="modal" data-target="#<?php echo $modal_id.$data_param?>">
    <?php echo $name?>
</button>
<!-- Modal Delete-->
<div class="modal fade in" id="<?php echo $modal_id.$data_param?>" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <?php echo form_open( $url );?>
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">#Delete <?php echo $title?></h4>
        </div>
        <div class="modal-body">
        <div style="word-wrap: break-word;" class="alert alert-danger">
            <div style="word-wrap: break-word !important;" >
            Apa Anda Yakin menghapus <b><?php echo $data->$data_name?></b> ?
            </div>
        </div>
        <!--  -->
        <?php 
            $_data["form_data"] = $form_data;
            $_data["data"] = $data;
            $this->load->view('templates/form/bsb_form', $_data );  
            ?>
        <!--  -->
        </div>
        <div class="modal-footer">
        <!-- <input type="hidden" class="form-control" value="<?php echo  $data->$param ?>" name="<?php echo  $param ?>" required="required"> -->
        <button type="submit" class="btn btn-danger">Ya</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Batal</button>
        </div>
        <?php echo form_close(); ?>
    </div>
    </div>
</div>
<!--  -->

