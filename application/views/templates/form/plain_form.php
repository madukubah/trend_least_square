
 <?php  $this->load->library( array( 'form_validation' ) );  ?>
 <!-- - -->
 <?php foreach( $form_data as $form_name => $attr ): ?>
    <div class="row">
        <div class="col-md-12">
                <?php
                    $form = array(
                        'name' => $form_name,
                        'type' => $attr['type'],
                        'placeholder' => $attr['label'],
                        'class' => 'form-control',  
                        
                    );
                    switch(  $attr['type'] )
                    {
                        case 'text':
                        case 'number':
                            $value = ( ( isset( $data ) && ( $data != NULL) )   ? $data->$form_name : ''  );
                            $form['value'] = ( isset( $attr['value'] )  ) ? $attr['value'] : $value;
                            echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                            echo form_input( $form );
                            break;
                        case 'hidden':
                            $value = ( ( isset( $data ) && ( $data != NULL) )   ? $data->$form_name : ''  );
                            $form['value'] = ( isset( $attr['value'] )  ) ? $attr['value'] : $value;
                            echo form_input( $form );
                            break;
                        case 'textarea':
                            $value = ( ( isset( $data ) && ( $data != NULL) )   ? $data->$form_name : ''  );
                            $form['rows'] = "5";
                            $form['value'] =  ( isset( $attr['value'] )  ) ? $attr['value'] : $value;
                            echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                            echo form_textarea( $form );
                            break;
                        case 'multiple_file':
                            $form['multiple'] = "";
                        case 'file':
                            echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                            echo form_upload( $form );
                            break;
                        case 'select':
                            $form['options'] = ( isset( $attr['options'] )  ) ? $attr['options'] : '';
                            $form['selected'] = ( isset( $attr['selected'] )  ) ? $attr['selected'] : '';
                            echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                            echo form_dropdown( $form );
                            break;
                    }
                ?>
        </div>
    </div>
<?php endforeach; ?>

<!--  -->