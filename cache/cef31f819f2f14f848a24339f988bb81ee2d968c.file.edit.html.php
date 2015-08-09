<?php /* Smarty version Smarty-3.0.7, created on 2015-08-09 17:02:07
         compiled from "application/views\pengaturan/operator/edit.html" */ ?>
<?php /*%%SmartyHeaderCode:3254655c76b6f0f9093-19204504%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cef31f819f2f14f848a24339f988bb81ee2d968c' => 
    array (
      0 => 'application/views\\pengaturan/operator/edit.html',
      1 => 1439132525,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3254655c76b6f0f9093-19204504',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script type="text/javascript">
$(document).ready(function() {
    // date picker
    /* KURANG DATEPICKER */
});
</script>

<section class="content-header">
    <h1>Ubah Data Operator</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/operator');?>
"> Operator</a></li>
        <li><a href="#">Ubah Data</a></li>
    </ol>
</section>
<!-- notification template -->
<?php $_template = new Smarty_Internal_Template("base/templates/notification.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<!-- end of notification template-->
<section class="content">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#">Data Pribadi</a></li>
            <li class=""><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url(('pengaturan/operator/account/').($_smarty_tpl->getVariable('result')->value['user_id']));?>
" >User Account</a></li>
        </ul>
        <div class="box-header ">

        </div><!-- /.box-header -->
        <!-- form start -->
        <form action="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/operator/edit_process');?>
" method="post">
            <div class="box-body">
                <div class="form-horizontal">
                    <input name="user_id" type="hidden"  value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['user_id'])===null||$tmp==='' ? '' : $tmp);?>
" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nama Lengkap</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="operator_name" type="text" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_name'])===null||$tmp==='' ? '' : $tmp);?>
" size="35" maxlength="50" />
                                    <small>* Wajib diisi</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tempat Lahir</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="operator_birth_place" type="text" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_birth_place'])===null||$tmp==='' ? '' : $tmp);?>
" size="25" maxlength="50" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tanggal Lahir</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="operator_birth_day" type="text" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_birth_day'])===null||$tmp==='' ? '' : $tmp);?>
" size="10" maxlength="10" class="tanggal" style="text-align: center;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Jenis Kelamin</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline"><input type="radio" name="operator_gender" value="L" <?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_gender'])===null||$tmp==='' ? '' : $tmp);?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1=='L'){?>checked="checked"<?php }?> />LAKI - LAKI</label>
                                    <label class="radio-inline"><input type="radio" name="operator_gender" value="P" <?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_gender'])===null||$tmp==='' ? '' : $tmp);?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2=='P'){?>checked="checked"<?php }?> />PEREMPUAN</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Alamat</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="operator_address" type="text" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_address'])===null||$tmp==='' ? '' : $tmp);?>
" size="45" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="user_mail" type="text" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['user_mail'])===null||$tmp==='' ? '' : $tmp);?>
" size="30" maxlength="50" />
                                    <small>* wajib diisi</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nomor Telepon</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="operator_phone" type="text" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_phone'])===null||$tmp==='' ? '' : $tmp);?>
" size="15" maxlength="30" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Jabatan</label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="operator_jabatan" type="text" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_jabatan'])===null||$tmp==='' ? '' : $tmp);?>
" size="50" maxlength="50" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="page-header">Foto</h2>
                <div class="row">
                    <div class="col-sm-4 img col-sm-offset-4">
                        <img class="img-responsive" src="<?php echo (($tmp = @$_smarty_tpl->getVariable('operator_photo')->value)===null||$tmp==='' ? '' : $tmp);?>
" alt="" style="background-color: #fff; border: 1px solid #ccc; padding: 5px;" />
                        <input class="form-control" type="file" name="operator_photo" size="30" />
                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
                <div class="col-lg-12">
                    <div class="pull-right">
                        <input type="reset" name="save" value="Reset" class="btn btn-default" />
                        <input type="submit" name="save" value="Simpan" class="btn btn-success" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
