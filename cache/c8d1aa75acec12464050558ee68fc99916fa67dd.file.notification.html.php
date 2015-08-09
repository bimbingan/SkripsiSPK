<?php /* Smarty version Smarty-3.0.7, created on 2015-08-09 11:55:06
         compiled from "application/views\base/templates/notification.html" */ ?>
<?php /*%%SmartyHeaderCode:6555c7237a0b7f67-06520362%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c8d1aa75acec12464050558ee68fc99916fa67dd' => 
    array (
      0 => 'application/views\\base/templates/notification.html',
      1 => 1439114102,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6555c7237a0b7f67-06520362',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!-- notification template -->
<?php if ((($tmp = @$_smarty_tpl->getVariable('notification_header')->value)===null||$tmp==='' ? '' : $tmp)=="error"){?>
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> <?php echo ((mb_detect_encoding($_smarty_tpl->getVariable('notification_header')->value, 'UTF-8, ISO-8859-1') === 'UTF-8') ? mb_strtoupper($_smarty_tpl->getVariable('notification_header')->value,SMARTY_RESOURCE_CHAR_SET) : strtoupper($_smarty_tpl->getVariable('notification_header')->value));?>
 </h4>
    <h5><?php echo $_smarty_tpl->getVariable('notification_message')->value;?>
</h5>
    <?php echo $_smarty_tpl->getVariable('notification_error')->value;?>

</div>
<?php }elseif((($tmp = @$_smarty_tpl->getVariable('notification_header')->value)===null||$tmp==='' ? '' : $tmp)=="success"){?>
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-check"></i> <?php echo ((mb_detect_encoding($_smarty_tpl->getVariable('notification_header')->value, 'UTF-8, ISO-8859-1') === 'UTF-8') ? mb_strtoupper($_smarty_tpl->getVariable('notification_header')->value,SMARTY_RESOURCE_CHAR_SET) : strtoupper($_smarty_tpl->getVariable('notification_header')->value));?>
 </h4>
    <h5><?php echo $_smarty_tpl->getVariable('notification_message')->value;?>
</h5>
    <?php echo $_smarty_tpl->getVariable('notification_error')->value;?>

</div>
<?php }else{ ?>
<?php }?>
<!-- End of notification template -->
