<?php /* Smarty version Smarty-3.0.7, created on 2015-08-09 06:32:23
         compiled from "application/views\login/operator/form.html" */ ?>
<?php /*%%SmartyHeaderCode:1783155c6d7d7acf8d8-80891810%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '354a7c05912a2c14ecbbf6e6de53872e18de2fef' => 
    array (
      0 => 'application/views\\login/operator/form.html',
      1 => 1438342934,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1783155c6d7d7acf8d8-80891810',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="login-body">    
    <?php if ((($tmp = @$_smarty_tpl->getVariable('login_st')->value)===null||$tmp==='' ? '' : $tmp)=='error'){?>
    <div class="alert">
        <p><img src="<?php echo $_smarty_tpl->getVariable('BASEURL')->value;?>
resource/doc/images/icon/alert.png" height="16" alt="" style="vertical-align: middle;" /> Login Failed</p>
        <p><strong>Your account is not found</strong>, Please Try Again or contact your administrator</p>
    </div>
    <form action="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('login/operatorlogin/login_process');?>
" method="post">
        <table class="table-login" width="100%">
            <tbody>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" maxlength="30" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="pass" maxlength="30" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Login"/></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php }elseif((($tmp = @$_smarty_tpl->getVariable('login_st')->value)===null||$tmp==='' ? '' : $tmp)=='locked'){?>
    <div class="alert">
        <p><img src="<?php echo $_smarty_tpl->getVariable('BASEURL')->value;?>
resource/doc/images/icon/alert.png" height="16" alt="" style="vertical-align: middle;" /> Login Failed</p>
        <p><strong>Your account has been locked</strong>, Please contact your administrator to activate your account</p>
    </div>
    <form action="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('login/operatorlogin/login_process');?>
" method="post">
        <table class="table-login" width="100%">
            <tbody>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" maxlength="30" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="pass" maxlength="30" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Login"/></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php }else{ ?>
    <div class="alert">
        <p><img src="<?php echo $_smarty_tpl->getVariable('BASEURL')->value;?>
resource/doc/images/icon/alert.png" height="16" alt="" style="vertical-align: middle;" /> You are enter restricted area</p>
        <p><strong>Please Login</strong> First to acces this application!</p>
    </div>
    <form action="<?php echo $_smarty_tpl->getVariable('config')->value->site_url('login/operatorlogin/login_process');?>
" method="post">
        <table class="table-login" width="100%">
            <tbody>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" maxlength="30" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="pass" maxlength="30" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Login"/></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php }?>
</div>