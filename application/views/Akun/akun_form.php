<?php
$form = array(
    'id_user' => array(
        'name'=>'id_user',
        'size'=>'30',
        'class'=>'form_field',
        'value'=>set_value('id_user', isset($form_value['id_user']) ? $form_value['id_user'] : '')
        ),
    'username'    => array(
        'username'=>'username',
        'size'=>'30',
        'class'=>'form_field',
        'value'=>set_value('username', isset($form_value['username']) ? $form_value['username'] : '')
        ),    
    'submit'   => array(
        'name'=>'submit',
        'id'=>'submit',
        'value'=>'Simpan'
        )
    );
    ?>

    <h2><?php echo $breadcrumb ?></h2>

    <!-- pesan start -->
    <?php if (! empty($pesan)) : ?>
        <div class="pesan">
            <?php echo $pesan; ?>
        </div>
    <?php endif ?>
    <!-- pesan end -->

    <!-- form start -->
    <?php echo form_open($form_action); ?>
    <p>
        <?php echo form_label('id_user', 'id_user'); ?>
        <?php echo form_input($form['id_user']); ?>
    </p>
    <?php echo form_error('id_user', '<p class="field_error">', '</p>');?>
    
    <p>
        <?php echo form_label('username', 'username'); ?>
        <?php echo form_input($form['username']); ?>
    </p>
    <?php echo form_error('username', '<p class="field_error">', '</p>');?>	

    <p>
      <?php echo form_submit($form['submit']); ?>
      <?php echo anchor('user','Batal', array('class' => 'cancel')) ?>
  </p>
  <?php echo form_close(); ?>
  <!-- form start -->

  <?php
  /* End of file akun_form.php */
  /* Location: ./application/views/Akun/siswa_form.php */
  ?>