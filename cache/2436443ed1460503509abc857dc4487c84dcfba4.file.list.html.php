<?php /* Smarty version Smarty-3.0.7, created on 2015-08-09 15:12:54
         compiled from "application/views\pengaturan/operator/list.html" */ ?>
<?php /*%%SmartyHeaderCode:1511355c751d67f26f9-93960952%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2436443ed1460503509abc857dc4487c84dcfba4' => 
    array (
      0 => 'application/views\\pengaturan/operator/list.html',
      1 => 1439125410,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1511355c751d67f26f9-93960952',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<section class="content-header">
    <h1>Pengaturan Operator</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
        <li><a href="#">Operator</a></li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Data Operator</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/operator/proses_cari');?>
" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="nama" class="col-sm-2 control-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="col-sm-10 form-control" id="nama" name="operator_name" value="<?php echo $_smarty_tpl->getVariable('search')->value['operator_name'];?>
" placeholder="-- semua --">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 pull-right action-right">
                        <input class="btn btn-primary " name="save" type="submit" value="Cari">
                        <input class="btn btn-danger" name="save" type="submit" value="Reset">
                    </div>
                </div>
            </form>
        </div><!-- /.box-body -->
    </div>
    <!-- notification template -->
    <?php $_template = new Smarty_Internal_Template("base/templates/notification.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
    <!-- end of notification template-->
    <div class="box">
        <div class="box-header with-border">
            <h5 class="box-title">Data Operator<small></small></h5>
            <div class="box-tools">
                <a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/operator/add');?>
" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp; Tambah</a>
            </div>
        </div>
        <div class="box-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="25%">Nama</th>
                        <th width="20%">Jabatan</th>
                        <th width="20%">E-mail</th>
                        <th width="15%">Phone Number</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $_smarty_tpl->tpl_vars['no'] = new Smarty_variable(1, null, null);?>
                    <?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('rs_id')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
?>
                    <tr <?php if (($_smarty_tpl->getVariable('no')->value%2)!=1){?>class="blink-row"<?php }?>>
                        <td align="center"><?php echo $_smarty_tpl->getVariable('no')->value++;?>
.</td>
                        <td><?php echo ((mb_detect_encoding($_smarty_tpl->tpl_vars['result']->value['operator_name'], 'UTF-8, ISO-8859-1') === 'UTF-8') ? mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['operator_name'],SMARTY_RESOURCE_CHAR_SET) : strtoupper($_smarty_tpl->tpl_vars['result']->value['operator_name']));?>
</td>
                        <td><?php echo ((mb_detect_encoding($_smarty_tpl->tpl_vars['result']->value['operator_jabatan'], 'UTF-8, ISO-8859-1') === 'UTF-8') ? mb_strtoupper($_smarty_tpl->tpl_vars['result']->value['operator_jabatan'],SMARTY_RESOURCE_CHAR_SET) : strtoupper($_smarty_tpl->tpl_vars['result']->value['operator_jabatan']));?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['result']->value['user_mail'];?>
</td>
                        <td align="center"><?php echo $_smarty_tpl->tpl_vars['result']->value['operator_phone'];?>
</td>
                        <td align="center">
                            <a class="btn bg-olive btn-flat btn-sm" href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url((($tmp = @('pengaturan/operator/edit/').($_smarty_tpl->tpl_vars['result']->value['user_id']))===null||$tmp==='' ? '' : $tmp));?>
" class="button-edit">Edit</a>
                            <a
                            class="btn btn-danger btn-flat btn-sm btn-delete"
                            href="#"
                            class="button-hapus" data-href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url((($tmp = @('pengaturan/operator/delete/').($_smarty_tpl->tpl_vars['result']->value['user_id']))===null||$tmp==='' ? '' : $tmp));?>
"
                            data-toggle="modal" data-target="#confirm-delete">Hapus</a>
                        </td>
                    </tr>
                    <?php }} else { ?>
                    <tr>
                        <td colspan="6">Data tidak ditemukan</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="col-sm-6">
                Menampilkan <b><?php echo (($tmp = @$_smarty_tpl->getVariable('pagination')->value['start'])===null||$tmp==='' ? 0 : $tmp);?>
</b> sampai <b><?php echo (($tmp = @$_smarty_tpl->getVariable('pagination')->value['end'])===null||$tmp==='' ? 0 : $tmp);?>
</b> dari <b><?php echo (($tmp = @$_smarty_tpl->getVariable('pagination')->value['total'])===null||$tmp==='' ? 0 : $tmp);?>
</b></strong> Data Operator
            </div>
            <ul class="pagination pagination-sm no-margin pull-right">
                <?php echo (($tmp = @$_smarty_tpl->getVariable('pagination')->value['data'])===null||$tmp==='' ? '' : $tmp);?>

            </ul>
        </div>
    </div>
</section>
