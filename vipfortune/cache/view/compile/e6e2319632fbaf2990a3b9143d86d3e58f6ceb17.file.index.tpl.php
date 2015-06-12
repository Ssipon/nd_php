<?php /* Smarty version Smarty-3.1.19, created on 2014-10-25 19:23:15
         compiled from "E:\PHPworkspace\vipfortune\view\error\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:31927544c5b135b5932-37321146%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6e2319632fbaf2990a3b9143d86d3e58f6ceb17' => 
    array (
      0 => 'E:\\PHPworkspace\\vipfortune\\view\\error\\index.tpl',
      1 => 1409657643,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31927544c5b135b5932-37321146',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'code' => 0,
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_544c5b13713eb0_75757301',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_544c5b13713eb0_75757301')) {function content_544c5b13713eb0_75757301($_smarty_tpl) {?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>错误提示<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
</title>
<style type="text/css">
<!--
.t {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        color: #CC0000;
}
.c {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 11px;
        font-weight: normal;
        color: #000000;
        line-height: 18px;
        text-align: center;
        border: 1px solid #CCCCCC;
        background-color: #FFFFEC;
}
body {
        background-color: #FFFFFF;
        margin-top: 100px;
}
-->
</style>
</head>
<body>
<div align="center">
  <h2><span class="t">站点异常</span></h2>
  <table border="0" cellpadding="8" cellspacing="0" width="460">
    <tbody>
      <tr>
        <td class="c"><?php echo $_smarty_tpl->tpl_vars['code']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</td>
      </tr>
    </tbody>
  </table>
  <div class="c">技术部-技术开发处一组</div>
</div>
</body>
</html><?php }} ?>
