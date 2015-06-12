<?php /* Smarty version Smarty-3.1.19, created on 2014-11-02 18:38:24
         compiled from "E:\PHPworkspace\vipfortune\view\template\box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18508544a5027ac0414-08926444%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '329e8325b28c71a5994b88b3f4ca4ddbc6d846d7' => 
    array (
      0 => 'E:\\PHPworkspace\\vipfortune\\view\\template\\box.tpl',
      1 => 1414981687,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18508544a5027ac0414-08926444',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_544a5027b39e01_53170142',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_544a5027b39e01_53170142')) {function content_544a5027b39e01_53170142($_smarty_tpl) {?><!--login-->
<div id="loginBox" style="display:none; z-index:999; left:50%; top:35%; margin-left:-222px!important; margin-top:-60px!important; margin-top:0px; position:fixed!important;/* FF IE7*/   position:absolute;/*IE6*/  _top: expression(eval(document.compatMode && document.compatMode=='CSS1Compat') ? documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/ document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/">
	<div class="tipbox box1">
		<div class="tip_t">
			<a href="javascript:;" onclick="hiddenlogBox();"  class="close">&times;</a>
			<h2>Login</h2>
		</div>
		<div class="tip_c">
			<div class="tip_con">
	    		<form id="loginform" name="loginform">
					<ul>
	            		<li>
	              			<label for="account">Account:</label>
	              			<input type="text" class="ipt" name="account" id="account"/>
	            		</li>
	            		<li>
	              			<label for="password">Password:</label>
	              			<input type="password" name="pwd" id="pwd" class="ipt" />
	            		</li>            
	            		<li>
	              			<label>Group:</label>
	              			<select id="area_id"  name="area_id" onChange="areaChanged(this.value, 0)" class="slt"></select>
	            		</li>
	            		<li>
	              			<label>Server:</label>
	              			<select id="server_id" name="server_id" class="slt"></select>
	            		</li>
	            		<li>
	              			<label for="v_code">Captcha: </label>
	              			<input type="text" class="ipt code" id="vcode" name="vcode" /> <span class="code_img"><img  border="0" id="_code" src="index.php?r=captcha/index"  onclick="flushCode();" style="position:relative; top:0px; left:9px;" align="top" width="80" height="40"/></span>
	              		</li>
	              		<li>
	              			<label for=""></label>
	              			<input type="checkbox" id="saveLogin" name="saveLogin" value="1" checked/> Remember Me?
	              		</li>
	          		</ul>
	          	</form>
    		</div>
		</div>
		<div class="tip_db">
			<ul class="two_btns clearfix">
    			<li><a href="javascript:;" class="btn_tip" title="LOGIN" onclick="doLogin();">LOGIN</a></li>
    			<li><a href="javascript:;" class="btn_tip" title="CANCEL" onclick="hiddenlogBox();">CANCEL</a></li>
    		</ul>
      		<p class="t_center mt10"><span style="margin-right:90px;"><a target="_blank" href="https://account.99.com/common/forgetpw.aspx">Forget Password?</a></span> <span><a href="https://account.99.com/common/signup.aspx?flag=co&amp;url=http://co.99.com" target="_blank">Haven't Registered?</a></span></p>
		</div>
	</div>
	<div class="mask"  id='logMask'></div>
</div>
<!--/login-->

<!--common msg-->
<div id="msgBox" style="display:none; z-index:1000; left:50%; top:35%; margin-left:-227px!important; margin-top:-60px!important; margin-top:0px; position:fixed!important;/* FF IE7*/   position:absolute;/*IE6*/  _top: expression(eval(document.compatMode && document.compatMode=='CSS1Compat') ? documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/ document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/">
	<div class="msgbox box1">
		<div class="tip_t">
			<a href="javascript:;" onclick="hiddenMsgBox();" class="close">&times;</a>
		</div>
		<div class="tip_c">
			<div class="tip_con">
	    		<p id='msg'></p>
    		</div>
		</div>
		<div class="tip_db">
			<a href="javascript:;" class="btn_tip" title="OK" onclick="hiddenMsgBox();">OK</a>
		</div>
	</div>
	<div class="msgmask" id='msgMask'></div>
</div>
<!--/common msg-->

<!--guidance-->
<div id="guidanceBox" style="display:none; z-index:999; left:50%; top:15%; margin-left:-381px!important; margin-top:-60px!important; margin-top:0px; position:fixed!important;/* FF IE7*/   position:absolute;/*IE6*/  _top: expression(eval(document.compatMode && document.compatMode=='CSS1Compat') ? documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/ document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/">
	<div class="tipbox box2">
		<div class="tip_t">
			<a  href="javascript:;" onclick='hiddenGuiBox();'  class="close">&times;</a>
			<h2>Guidance</h2>
		</div>
		<div class="tip_c">
			<div class="tip_con">
				<div class="infoscroll">
		    		<p><strong>Duration: </strong>Nov. 3rd to Nov. 16th</p>
					<ol>
					  <li>During the event, if you credit  a certain TQ Point Card, you will be rewarded with Points, which can be used to  spin the wheel for grand prizes. </li>
					</ol>
					<div>
					  <table>
					    <tr>
					      <td><strong>Type of TQ Point Card</strong></td>
					      <td><strong>Credits Gained</strong></td>
					    </tr>
					    <tr>
					      <td>$7.99 TQ Point Card</td>
					      <td>5</td>
					    </tr>
					    <tr>
					      <td>$15.99 TQ Point Card</td>
					      <td>10</td>
					    </tr>
					    <tr>
					      <td>$29.99 TQ Point Card</td>
					      <td>20</td>
					    </tr>
					    <tr>
					      <td>$59.99 TQ Point Card</td>
					      <td>40</td>
					    </tr>
					  </table>
					</div>
					<ol>
					  <li>Each spin will cost 10 Credits.  When you spin the wheel and use enough Credits, you'll be able to spin a higher  level Wheel of Fortune. There are 4 levels of Wheel of Fortune, and each one  gives different rewards.</li>
					</ol>
					<div>
					  <table>
					    <tr>
					      <td><strong>Type of Wheel of Fortune</strong></td>
					      <td><strong>Used Credits</strong></td>
					    </tr>
					    <tr>
					      <td>Level 1</td>
					      <td>≤100 Credits</td>
					    </tr>
					    <tr>
					      <td>Level 2</td>
					      <td>≥110 Credits and ≤500 Credits</td>
					    </tr>
					    <tr>
					      <td>Level 3</td>
					      <td>≥510 Credits and ≤1,000 Credits</td>
					    </tr>
					    <tr>
					      <td>Level 4</td>
					      <td>≥1,010 Credits</td>
					    </tr>
					  </table>
					</div>
					<ol>
					  <li>There's a ranking once you  reach the Level 4 Wheel of Fortune. The more you credit, the higher you'll be  ranked. The Top 10 players will receive a special garment!</li>
					</ol>
					<div>
					  <table>
					    <tr>
					      <td><strong>Rank</strong></td>
					      <td><strong>Reward</strong></td>
					    </tr>
					    <tr>
					      <td>1</td>
					      <td>30-day 3% C-Strike, 3% Immunity    Endless Dance</td>
					    </tr>
					    <tr>
					      <td>2</td>
					      <td>30-day 2% C-Strike, 2% Immunity    Endless Dance</td>
					    </tr>
					    <tr>
					      <td>3</td>
					      <td>30-day 2% C-Strike, 2% Immunity    Endless Dance</td>
					    </tr>
					    <tr>
					      <td>4-10</td>
					      <td>30-day 1% C-Strike, 1% Immunity    Endless Dance</td>
					    </tr>
					  </table>
					</div>
					<h4>VIP Sprint</h4>
					<p>Duration:  Nov. 3rd to Nov. 16th </p>
					<p>Rules:</p>
					<ol>
					  <li>During the event, all VIP  players who upgrade to a new VIP level will be rewarded as follows!</li>
					</ol>
					<div>
					  <table>
					    <tr>
					      <td><strong>VIP Level</strong></td>
					      <td><strong>Gift Pack</strong></td>
					      <td><strong>Items</strong></td>
					      <td><strong>Upgrade Req.</strong></td>
					      <td><strong>Reward Value</strong></td>
					    </tr>
					    <tr>
					      <td><strong>&nbsp;</strong></td>
					      <td><strong>&nbsp;</strong></td>
					      <td><strong>&nbsp;</strong></td>
					      <td><strong>&nbsp;</strong></td>
					      <td><strong>&nbsp;</strong></td>
					    </tr>
					    <tr>
					      <td>0    -&gt; 1</td>
					      <td>VIP1    Honor Pack</td>
					      <td>3    Small Lottery Tickets.</td>
					      <td>$59</td>
					      <td>81    CPs</td>
					    </tr>
					    <tr>
					      <td>1    -&gt; 2</td>
					      <td>A    Dragon Ball</td>
					      <td>a    Dragon Ball</td>
					      <td>$140</td>
					      <td>215    CPs</td>
					    </tr>
					    <tr>
					      <td>2    -&gt; 3</td>
					      <td>VIP3    Honor Pack</td>
					      <td>1    Flood Demon Box, 3 Favored Training Pills (B), 500 Study Points (B) and 2 +3    Stones between Nov. 14th - Dec. 14th.</td>
					      <td>$200</td>
					      <td>298    CPs</td>
					    </tr>
					    <tr>
					      <td>3    -&gt; 4</td>
					      <td>VIP4    Honor Pack</td>
					      <td>Heaven    Demon Box, Vital Pills(B), Senior Training Pills(B), Permanent Stone and VIP4    Freedom Box between Nov.14 - Dec.14.</td>
					      <td>$600</td>
					      <td>1,315    CPs</td>
					    </tr>
					    <tr>
					      <td>4    -&gt; 5</td>
					      <td>VIP5    Honor Pack</td>
					      <td>Permanent    Stone, Senior Training Pills(B), Vital Pills(B), Fancy Alpaca, +4 Stone and    VIP5 Freedom Box from Nov.14-Dec.14.</td>
					      <td>$1,000</td>
					      <td>2,260    CPs</td>
					    </tr>
					    <tr>
					      <td>5    -&gt; 6</td>
					      <td>VIP6    Honor Pack</td>
					      <td>Vital    Pills(B), Winged Frostcat Armor, Power EXP Ball(B), Senior Training Pills,    VIP6 Freedom Box, etc. from Nov.14 - Dec.14.</td>
					      <td>$2,000</td>
					      <td>4,746    CPs</td>
					    </tr>
					    <tr>
					      <td>6    -&gt; 7</td>
					      <td>VIP7    Honor Pack</td>
					      <td>Big    Permanent Stone, Senior Training Pills, Vital Pills, Love Horse, Power EXP Balls(B),    VIP7 box, etc. from Nov.14 - Dec.14.</td>
					      <td>$4,000</td>
					      <td>9,724    CPs</td>
					    </tr>
					    <tr>
					      <td>VIP    7+</td>
					      <td>Super    Honor Pack</td>
					      <td>Vital    Pills, P7 Equipment Soul Pack, Senior Training Pills(B), Precious Garment Pack    and VIP7 Freedom Box from Nov.14-Dec.14.</td>
					      <td>$2,000</td>
					      <td>11,961    CPs</td>
					    </tr>
					  </table>
					</div>
					<p><em>Note: VIP 7+ indicates that after you reach VIP Level 7 and you  credit $2,000 USD to your account every time, you'll be rewarded, every time!</em></p>
					<ol>
					  <li>If you go from VIP 0 - VIP 7,  you'll be able to claim all 8 Honor Packs. Except Super Honor Pack, all players  can only claim each one once. You can claim Super Honor Pack every time you  credit $2,000 USD to your account after reaching VIP Level 7.</li>
					</ol>
				</div>	
    		</div>
		</div>
		<div class="tip_db">
			<a href="javascript:;" class="btn_tip" title="OK" onclick="hiddenGuiBox();">OK</a>
		</div>
	</div>
	<div class="mask" id='guidanceMask'></div>
</div>
<!--/guidance-->

<!--submit bugs-->
<div id="bugBox" style="display:none; z-index:999; left:50%; top:35%; margin-left:-281px!important; margin-top:-60px!important; margin-top:0px; position:fixed!important;/* FF IE7*/   position:absolute;/*IE6*/  _top: expression(eval(document.compatMode && document.compatMode=='CSS1Compat') ? documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/ document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/">
	<div class="contentbox box3">
		<div class="tip_t">
			<a href="javascript:;" class="close"  onclick='hiddenBugBox()'>&times;</a>
			<h2>Submit Bugs</h2>
		</div>
		<div class="tip_c">
			<div class="tip_con">
	    		<textarea name="message" placeholder = 'Please input the details of bugs...'  id="bugContent" maxlength = '300' minlength='10'></textarea>
    		</div>
		</div>
		<div class="tip_db">
			<a href="javascript:;" class="btn_tip" title="Submit" onclick="doSubmit(0);">Submit</a>
		</div>
	</div>
	<div class="mask"  id='bugMask'></div>
</div>
<!--/submit bugs-->
<!--submit suggestion-->
<div id="sugBox" style="display:none; z-index:999; left:50%; top:35%; margin-left:-281px!important; margin-top:-60px!important; margin-top:0px; position:fixed!important;/* FF IE7*/   position:absolute;/*IE6*/  _top: expression(eval(document.compatMode && document.compatMode=='CSS1Compat') ? documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/ document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/">
	<div class="contentbox box3">
		<div class="tip_t">
			<a href="javascript:;" class="close" onclick='hiddenSugBox()'>&times;</a>
			<h2>Suggestion</h2>
		</div>
		<div class="tip_c">
			<div class="tip_con">
	    		<textarea name="message" placeholder ='Please input the details of your suggestion...' id="sugContent" maxlength = '300' minlength='10'></textarea>
    		</div>
		</div>
		<div class="tip_db">
			<a href="javascript:;" class="btn_tip" title="Submit" onclick="doSubmit(1);">Submit</a>
		</div>
	</div>
	<div class="mask" id="sugMask" ></div>
</div>
<!--/submit suggestion-->

<div id='loading' class='loading' style="display: none;"></div><?php }} ?>
