<?php /* Smarty version Smarty-3.0.7, created on 2015-08-09 10:01:19
         compiled from "application/views\pengaturan/preference/profil_sekolah/list.html" */ ?>
<?php /*%%SmartyHeaderCode:762255c708cfd8ad22-23518270%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '154eecd11a2ad37ca0fa48debda9ef7fb17f20f7' => 
    array (
      0 => 'application/views\\pengaturan/preference/profil_sekolah/list.html',
      1 => 1438342938,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '762255c708cfd8ad22-23518270',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script type="text/javascript">
    $(document).ready(function() {
        // select box perusahaan
        $(".perusahaan").select2({
            allowClear: true
        });
    });
</script> 
<div class="breadcrum">
    <p>
        <a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference');?>
">Settings</a><span></span>
        <a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference');?>
">Preference</a><span></span>
        <small>Profil Sekolah</small>
    </p>
    <div class="clear"></div>
</div>
<div class="sub-nav-content">
    <ul>
        <li><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference/profil_sekolah');?>
" class="active">Profil Sekolah</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference/kelas');?>
">Kelas</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference/tingkat');?>
">Tingkat Pendidikan</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference/agama');?>
">Agama</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference/psb');?>
" >PSB</a></li>

    </ul>
    <div class="clear"></div>
</div>
<!-- notification template -->
<?php $_template = new Smarty_Internal_Template("base/templates/notification.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<!-- end of notification template-->
<form action="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('pengaturan/preference/save_profil_sekolah');?>
" method="post">
    <input type="hidden" name="nama_id" value="<?php echo $_smarty_tpl->getVariable('rs_nama')->value[0]['pref_id'];?>
">
    <input type="hidden" name="alamat_id" value="<?php echo $_smarty_tpl->getVariable('rs_alamat')->value[0]['pref_id'];?>
">
    <input type="hidden" name="tingkat_id" value="<?php echo $_smarty_tpl->getVariable('rs_tingkat_sekolah')->value[0]['pref_id'];?>
">
    <table class="table-input" width="100%">
        <tr class="headrow">
            <th colspan="2">Edit Profil Sekolah</th>
        </tr>
        <tr>
            <td width="20%">Nama Sekolah</td>
            <td width="80%"><input type="text" name="sekolah_nama" maxlength="50" size="40" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('rs_nama')->value[0]['pref_value'])===null||$tmp==='' ? '' : $tmp);?>
" /><em>* wajib diisi</em></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><input type="text" name="sekolah_alamat" maxlength="100" size="70" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('rs_alamat')->value[0]['pref_value'])===null||$tmp==='' ? '' : $tmp);?>
" /><em>* wajib diisi</em></td>
        </tr>
        <tr>
            <td>Tingkat</td>
            <td>
                <select name="sekolah_tingkat">
                    <?php  $_smarty_tpl->tpl_vars['tingkat'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('rs_tingkat')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['tingkat']->key => $_smarty_tpl->tpl_vars['tingkat']->value){
?>
                    <option <?php if ($_smarty_tpl->getVariable('rs_tingkat_sekolah')->value['pref_value']==$_smarty_tpl->tpl_vars['tingkat']->value['pref_value']){?>selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['tingkat']->value['pref_value'];?>
"><?php echo $_smarty_tpl->tpl_vars['tingkat']->value['pref_value'];?>
</option>
                    <?php }} ?>
                </select>
            </td>
        </tr>
        <tr class="submit-box">
            <td colspan="2">
                <input type="submit" name="save" value="Simpan" class="submit-button" />
                <input type="reset" name="save" value="Reset" class="reset-button" />
            </td>
        </tr>
    </table>
</form> 