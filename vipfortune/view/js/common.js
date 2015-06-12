function showlogBox() {
	$("#loginBox").show();
	$("#logMask").show();
}

function hiddenlogBox() {
	$('#loginBox').hide();
	$("#logMask").hide();
	$("#mask").hide();
	$("#account").val('');
	$("#pwd").val('');
	$("#vcode").val('');
}

function showMsgBox(msg) {
	$("#msgMask").show();
	$("#msgBox").show();
	$("#msg").html(msg);
}

function hiddenMsgBox() {
	$("#msgMask").hide();
	$("#msgBox").hide();
}

function showBugBox(isLogin) {
	if ( ! isLogin ) {
		showlogBox(); 
		return ;
	}
	$("#bugMask").show();
	$("#bugBox").show();
}

function hiddenBugBox() {
	$("#bugMask").hide();
	$("#bugBox").hide();
	$("#bugContent").val('')
}

function showSugBox(isLogin) {
	if ( ! isLogin ) {
		showlogBox(); 
		return ;
	}
	$("#sugMask").show();
	$("#sugBox").show();
}

function hiddenSugBox() {
	$("#sugMask").hide();
	$("#sugBox").hide();
	$("#sugContent").val('')
}

function showGuiBox(isLogin) {
	$("#guidanceMask").show();
	$("#guidanceBox").show();
}

function hiddenGuiBox() {
	$("#guidanceMask").hide();
	$("#guidanceBox").hide();
}
/**
 * 刷新验证码
 */
function flushCode(){
	$("#_code").attr('src',"index.php?r=captcha/index&"+Math.random());
}

function doLogin() {
	var account = $.trim($("#account").val());
	var pwd = $.trim($("#pwd").val());
	if( pwd == '' ) {
		showMsgBox('Please input your password!');
		return false ;
	}
	if(account == '' ) {
		showMsgBox('Please input your account!');
		return false ;
	}
	var vcode = $.trim($("#vcode").val());
	if(vcode == '') {
		showMsgBox('Please input the validation code.');
		return false ;
	}
	var serializeValue=$('#formID').serialize();
	$.ajax({
			 url : '?r=/login/login',
			type : 'POST',
			data : $('#loginform').serialize(), 
	    dataType : 'json',
	  beforeSend : function() {
				$('#loading').show();
  		   },
		   error : function(){
			    $('#loading').hide();
                showMsgBox('Abnormal network cause! Failed to submit. Please submit again later.');
                flushCode();
		   }, 
	     success : function(data) {
		    	$('#loading').hide();
				if ( typeof ( data.code ) != 'undefined' && 1 == data.code) {
					location.reload();//登陆成功，重载页面
					return ;
				} 
				showMsgBox(data.msg);
				flushCode();}
			});
}

function signout() {
	$.ajax({
		 url : '?r=/login/logout',
		type : 'POST',
    dataType : 'html',
    beforeSend : function() {
			$('#loading').show();
	   },
	   error : function(){ showMsgBox('Abnormal network cause! Failed to submit. Please submit again later.');}, 
    success : function(data) {
			window.location.href="index.php"; 
		}});
}


/**
 * 内容提交方法类
 */
function doSubmit(type) {
	if( 0 == type ) 
		var content = $.trim($("#bugContent").val()); 
	else 
		var content = $.trim($("#sugContent").val());
	
	if( content == '' ) {
		showMsgBox('Please input 10 to 300 characters to submit bugs or suggestions.');
		return false ;
	}
	
	if( 10 > content.length || 300 < content.length ) {
		showMsgBox(' Please input 10 to 300 characters to submit bugs or suggestions.');
		return false ;
	}
	
	$.ajax({
			 url : '?r=/bugNSuggestion/exc',
			type : 'POST',
			data : {'type':type,'content':content} ,
	    dataType : 'json',
	  beforeSend : function() {
				$('#loading').show();
  		   },
		   error : function(){
			    $('#loading').hide();
                showMsgBox('Abnormal network cause! Failed to submit. Please submit again later.');}, 
	     success : function(data) {
	    	 	if ( typeof ( data.isLogin ) != 'undefined' && false == data.isLogin ) {
					$('#loading').hide();
					showlogBox(); 
					return;
				} else if ( typeof ( data.isSubmit ) != 'undefined' && data.isSubmit ) {
					hiddenSugBox();
					hiddenBugBox();
				} 
				$('#loading').hide();
				showMsgBox(data.msg); }
			});
}

function doScore(num,isLogin) {
	if ( ! isLogin ) {
		showlogBox(); 
		return ;
	}
	
	$.ajax({
  		type: "POST",
  		url: "?r=/score/exc",
  		data: {'star':num},
  		dataType: "json",
  		beforeSend: function() {
  			$('#loading').show();
  		},
  		error:	function(XMLHttpRequest, textStatus, errorThrown) {  
  			$('#loading').hide();
  			showMsgBox('Abnormal network cause! Failed to submit. Please submit again later.'); 
		},
		complete:function() {
			$('#loading').hide();
		},
  		success: function (data) {
  			$('#loading').hide();
  			if ( typeof ( data.isLogin ) != 'undefined' && false == data.isLogin ) {
				showlogBox(); 
				return;
			} else if ( typeof ( data.succeed ) != 'undefined' && data.succeed ) {
				$('#score_num2').text(data.avgScore); 
				$('#vote_total').text(data.voteCount); 
			} 
			showMsgBox(data.msg); }
	});
	return;
}

function loadStar() {
	$.getJSON('?r=/index/loadStar', function(data){
		overstar(parseInt(data['star']));
		$('#score_num').text(data['star']);
		$('#vote_total').text(data['votenum']);
		
	});
}

function overstar(num) {
	$("a[id^=star_]").removeClass();
	
	for (var i=1; i<=parseInt(num); i++) {
		$('#star_'+i).addClass("on");
	}

}

var p ; //全局分页标签变量
$(function(){

	$("a.showrank").click(function(){
      $(this).hide();
      $(".poprank").show();
          p = new NdLib.Page('?r=/index/list', '.pages', 
    		  function (data) {
    	      var html = '';
    	      for(var i in data.list) {
    		    html += '<tr>';
    		    html += '	<td>'+data.list[i].no+'</td>';
    	    	html += '	<td><div class="lw" title="' + data.list[i].player_name + '">' + data.list[i].player_name + '</div></td>';
    	    	html += '	<td><div class="lw" title="' + data.list[i].server_name + '">' + data.list[i].server_name + '</div></td>';
    	    	html += '	<td><div class="lw" title="' + data.list[i].area_name + '">' + data.list[i].area_name + '</div></td>';
    	    	html += '	<td>' + data.list[i].credit_used + '</td>';
    	    	html += '</tr>';
    	     }
    	     //分页
    	     $('#rank_list').html(html);
    	     $('.pages').html(this.getPageHtml(data.page, data.total));
    	}).init({pageSize:10, sort:1}, {
    	    header   : true,
    	    first    : 'First',
    	    next     : 'Next',
    	    pre      : 'Prev',
    	    last     : 'Last',
    	    curStyle : 'on'
    	});
    })
    
    $("a.hiderank").click(function(){
      $(".poprank").hide();
      $("a.showrank").show();
    })
    
    //
    $(".note a").hover(function(){
		$(this).next().show();
	},function(){
		$(this).next().hide();
	})

});

function doSearch(){
	var charact = $.trim($("#charact").val());
	if(charact == '' || 'Input the character name…' == charact ) {
		showMsgBox('Please input your charact!');
		return false ;
	}
	p.setParams('playerName',charact);
	p.setParams('serverId',$("#server_id2").val());
	p.setParams('areaId',$("#area_id2").val());
	p.reLoad(1);
}
 
