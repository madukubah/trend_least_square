<?php echo (!isset($detail)) ? form_open($form_action) : null;?>
<?php foreach ($form_data as $key => $value): ?>
  <?php if ($value['type']!='hidden'): ?>
    <div class="col-sm-12">
      <div class="form-group form-float">
        <div class="form-line">
          <?php
            switch ($value['type']) {
              case 'select':
                  $label =  $value['placeholder'];
                  $options =  $value['option'];
                  $name = $value['name'];
                  $selected = $value['value'];
                  unset($value['placeholder']);
                  unset($value['option']);
                  unset($value['name']);
                  echo "<p' class='text-mute'>$label</p>";
                  echo form_dropdown($name, $options, $selected, $value);
                break;
              case 'textarea':
                $label =  $value['placeholder'];
                unset($value['placeholder']);
                echo "<label class='form-label'>$label</label>";
                echo form_textarea($value);
                break;
              default:
                  $label =  $value['placeholder'];
                  unset($value['placeholder']);
                  echo "<label class='form-label'>$label</label>";
                  echo form_input($value);
                break;
            }
          ?>
        </div>
      </div>
    </div>
  <?php else: ?>
    <?php
      $name = $value['name'];
      $val = $value['value'];
      echo form_hidden($name, $val);
     ?>
  <?php endif; ?>

<?php endforeach; ?>
  <?php if (!isset($detail)): ?>
    <div class="col-sm-12 ">
        <button type="clear" class="btn float-left btn-warning waves-effect">Clear</button>
        <button type="submit" class="btn float-left btn-primary waves-effect">Simpan</button>
    </div>
    <?php echo form_close();?>
  <?php endif; ?>
