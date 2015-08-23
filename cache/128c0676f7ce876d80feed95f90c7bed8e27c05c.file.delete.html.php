<?php /* Smarty version Smarty-3.0.7, created on 2015-08-11 13:43:38
         compiled from "application/views\pengaturan/operator/delete.html" */ ?>
<?php /*%%SmartyHeaderCode:122455c9dfeaac5687-12077319%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '128c0676f7ce876d80feed95f90c7bed8e27c05c' => 
    array (
      0 => 'application/views\\pengaturan/operator/delete.html',
      1 => 1439279759,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122455c9dfeaac5687-12077319',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<section class="content-header">
    <h1>Detail Operator</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/operator');?>
"> Operator</a></li>
        <li><a href="#"><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_name'])===null||$tmp==='' ? '' : $tmp);?>
</a></li>
    </ol>
</section>
<!-- notification template -->
<?php $_template = new Smarty_Internal_Template("base/templates/notification.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<!-- end of notification template-->
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Apakah anda yakin akan menghapus data dibawah ini?</h3>
            <div class="box-tools">
                <a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/operator');?>
" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
            </div>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form action="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/operator/delete_process');?>
" method="post" onsubmit="return confirm('Apakah anda yakin akan menghapus data berikut ini?')">
            <div class="box-body">
                <div class="row">
                    <input name="user_id" type="hidden"  value="<?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['user_id'])===null||$tmp==='' ? '' : $tmp);?>
" />
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="50%">Nama Lengkap</td>
                                        <td width="50%"><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_name'])===null||$tmp==='' ? '' : $tmp);?>
</td>

                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td>
                                            <?php if ((($tmp = @$_smarty_tpl->getVariable('result')->value['operator_gender'])===null||$tmp==='' ? '' : $tmp)=='L'){?>Laki - Laki<?php }?>
                                            <?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_gender'])===null||$tmp==='' ? '' : $tmp);?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1=='P'){?>Perempuan<?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tempat Lahir</td>
                                        <td><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_birth_place'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Lahir</td>
                                        <td><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_birth_day'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="50%">Alamat</td>
                                        <td width="50%"><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_address'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['user_mail'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Telepon</td>
                                        <td><?php echo (($tmp = @$_smarty_tpl->getVariable('result')->value['operator_phone'])===null||$tmp==='' ? '' : $tmp);?>
</td>
                                    </tr>
                                    <tr>
                                        <th>Foto :</th>
                                        <td>
                                            <img src="<?php echo (($tmp = @$_smarty_tpl->getVariable('operator_photo')->value)===null||$tmp==='' ? '' : $tmp);?>
" alt="" style="height: 160px; background-color: #fff; border: 1px solid #ccc; padding: 5px;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
                <input type="submit" name="save" value="Hapus" class="btn btn-danger pull-right" />
            </div>
        </form>
    </div>
</section>
