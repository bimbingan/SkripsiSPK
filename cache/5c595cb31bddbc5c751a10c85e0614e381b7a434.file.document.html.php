<?php /* Smarty version Smarty-3.0.7, created on 2015-08-09 15:01:24
         compiled from "application/views\base/operator/document.html" */ ?>
<?php /*%%SmartyHeaderCode:170055c74f24739574-28778597%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5c595cb31bddbc5c751a10c85e0614e381b7a434' => 
    array (
      0 => 'application/views\\base/operator/document.html',
      1 => 1439124619,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170055c74f24739574-28778597',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_capitalize')) include 'D:\xampp\htdocs\skripsi-spk.1.0\system\plugins\smarty\libs\plugins\modifier.capitalize.php';
?><!DOCTYPE html>
<html lang="en">
<!-- head -->
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
    <meta name='description' content="<?php echo (($tmp = @$_smarty_tpl->getVariable('site')->value['meta_desc'])===null||$tmp==='' ? '' : $tmp);?>
" />
    <meta name='keywords' content="<?php echo (($tmp = @$_smarty_tpl->getVariable('site')->value['meta_key'])===null||$tmp==='' ? '' : $tmp);?>
" />
    <meta name='robots' content='index,follow' />
    <title><?php echo (($tmp = @$_smarty_tpl->getVariable('page')->value['nav_title'])===null||$tmp==='' ? 'Home' : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->getVariable('site')->value['site_title'])===null||$tmp==='' ? '' : $tmp);?>
</title>
    <link href="<?php echo $_smarty_tpl->getVariable('BASEURL')->value;?>
/resource/doc/images/icon/logo.png" rel="SHORTCUT ICON" />
    <!-- themes style -->
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('THEMESPATH')->value;?>
" media="screen" />
    <!-- other style -->
    <?php echo $_smarty_tpl->getVariable('LOAD_STYLE')->value;?>

</head>
<!-- body -->
<body  class="skin-green-light sidebar-mini">
    <!-- load javascript -->
    <?php echo $_smarty_tpl->getVariable('LOAD_JAVASCRIPT')->value;?>

    <!-- end of javascript	-->
    <!-- layout -->
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="http://localhost/skripsiSPK/index.php/home" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>SPK</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>SPK</b>&nbsp; Peminatan</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <img src="<?php echo $_smarty_tpl->getVariable('com_user')->value['operator_photo'];?>
" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo $_smarty_tpl->getVariable('com_user')->value['operator_name'];?>
</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo $_smarty_tpl->getVariable('com_user')->value['operator_photo'];?>
" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo $_smarty_tpl->getVariable('com_user')->value['operator_name'];?>

                                    </p>
                                    <p>
                                        <?php echo smarty_modifier_capitalize($_smarty_tpl->getVariable('com_user')->value['role_nm']);?>

                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="image">
                        <img width="500px" src="<?php echo $_smarty_tpl->getVariable('BASEURL')->value;?>
/resource/img/icon/logo.png" alt="Logo">
                    </div>
                </div>
                <!-- search form -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('template_sidebar')->value), $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
            </section>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
            <?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('template_content')->value), $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
        </div>
        <footer class="main-footer">
            Copyright &copy;2015 SMA Negeri 1 Bojong <br/>
            sma_bojong@yahoo.com
        </footer>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm warning">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><i class="fa fa-exclamation-triangle"></i> Perhatian</h4>
                </div>
                <div class="modal-body">
                    Apakah anda yakin?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    });
    </script>
</body>
<!-- end body -->
</html>
