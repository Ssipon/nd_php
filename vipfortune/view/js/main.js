NdLib = {};
NdLib.Page = function (url, gate, callback, showLoading) {
    this.setting = {
        header   : false,
        first    : '首页',
        next     : '下一页',
        pre      : '上一页',
        last     : '最后一页',
        curStyle : 'page_cur',
        noStyle  : '',
        step     : 5
    };

    this.baseParams  = {pageSize : 10, page : 1};
    this.callback    = callback;
    this.url         = url;
    this.showLoading = showLoading || null;
    this.showLoadTimeObj = null;
    this.pageObj     = $(gate);

    this.clearTime = function () {
        if (this.showLoadTimeObj) {
            clearTimeout(this.showLoadTimeObj);
            this.showLoadTimeObj = null;
        }
    };
};

NdLib.Page.prototype.init = function (params, setting) {
    $.extend(this.baseParams, params || {});
    $.extend(this.setting, setting || {});

    var self = this;
    this.pageObj.undelegate('.mypagebar', 'click');
    this.pageObj.delegate('.mypagebar', 'click', function (e) {
        e.preventDefault();
        if($(this).attr('href') != 'javascript:;') {
            self.baseParams['page'] = $(this).attr('page');
            self.doRequest();
        }
    });

    this.doRequest();

    return this;
};

NdLib.Page.prototype.isset = function (a) {
    return "undefined" != typeof a && null != a;
};

NdLib.Page.prototype.intval = function (a, c) {
    a = parseInt(a || 0, 10);
    if (true == isNaN(a)) {
        a = 0;
    }

    if (a < 0 && true != c) {
        a = 0
    }

    return a;
};

NdLib.Page.prototype.setParams = function (key, value) {
    if (typeof value == 'undefined') {
        $.extend(this.baseParams, key || {});
    } else {
        this.baseParams[key] = value;
    }

    return this;
};

NdLib.Page.prototype.clrParams = function (key) {
    if (!!this.baseParams[key]) {
        this.baseParams[key] = '';
        delete this.baseParams[key];
    }

    return this;
};

NdLib.Page.prototype.getPageHtml = function (page, total) {
    page  = this.intval(page);
    this.baseParams['page'] = page;

    total = this.intval(total);
    total = Math.ceil(total / parseInt(this.baseParams['pageSize']));
    if (total <= 0) {
        return '';
    }

    var a = [];
    if (this.setting.header) {
        a.push('<span>total : </span><span>' + page + '/' + total + '</span>&nbsp;&nbsp;');
    }

    if (page == 1) {
        a.push('<a class="mb">' + this.setting.first + '</a>');
        a.push('<a class="mb">' + this.setting.pre + '</a>');
    } else {
        a.push('<a href="#" page="1" class="mypagebar mb">' + this.setting.first + '</a>');
        a.push('<a href="#" page="' + (page-1) + '" class="mypagebar mb">' + this.setting.pre + '</a>');
    }

    var s; //开始位置
    var e; //结束位置
    var step = this.setting.step;
    var mid  = Math.ceil(step / 2);
    if (step - mid + page > total) {
        mid = step - total + page;
    }
    s = page - mid + 1;
    s = s >= 1 ? s : 1;
    e = s + step;
    for(var f=s; f<=e; f++) {
        if (f <= total) {
            if(page == f) {
                a.push('<a class="' + this.setting.curStyle + '">' + f + '</a>')
            } else {
                a.push('<a href="javascript:void(0);" class="mypagebar ' + this.setting.noStyle + '" page="'+ f +'">' + f + '</a>');
            }
        } else {
            break;
        }
    }

    if (page >= total) {
        a.push('<a class="mb">' + this.setting.next + '</a>');
        a.push('<a class="mb">' + this.setting.last + '</a>');
    } else {
        a.push('<a href="#" page="'+ (page+1) +'" class="mypagebar mb">' + this.setting.next + '</a>');
        a.push('<a href="#" page="'+ total +'" class="mypagebar mb">' + this.setting.last + '</a>');
    }

    return a.join("");
};

NdLib.Page.prototype.reLoad = function (page) {
    this.baseParams['page'] = page || this.baseParams['page'];
    this.doRequest();
};

NdLib.Page.prototype.doRequest = function () {
    var self = this;

    $.ajax({
        url  : self.url,
        type : "post",
        dataType : 'json',
        data : this.baseParams,
        success:function(data) {
            self.clearTime();
            self.callback && self.callback(data);
        },
        beforeSend:function(){
            self.clearTime();
            self.showLoadTimeObj = setTimeout(function () {
            }, 200);
        },
        error:function(){
            self.clearTime();
        }
    });
};

//document.getElementById
function _(element) {
    if (arguments.length > 1) {
        for (var i = 0, elements = [], length = arguments.length; i < length; i++)
            elements.push($(arguments[i]));
        return elements;
    }
    else return document.getElementById(element);
}

//init the server list
function areaChanged(area_id, server_id, area_id_field, server_id_field) {
	var area_id_field = area_id_field?area_id_field:'area_id';
	var server_id_field = server_id_field?server_id_field:'server_id';
	
    var oAreaId   = _(area_id_field);
    var oServerId = _(server_id_field);
	
	server_id = !!server_id ? server_id : 1;

    var area = serverList[0];
    if (0 == area_id) var area = '';//TODO
    
    for (var i = 0; i < serverList.length; i++) 
	{
        if (serverList[i].id == area_id) 
		{
            area = serverList[i];
            break;
        }
    }

    if (area) 
	{
        oServerId.options.length = 0;
        for (var i = 0; i < area.sub.length; i++) 
		{
            oServerId.options.add(new Option(area.sub[i].name, area.sub[i].id));
            if (server_id == area.sub[i].id) oServerId.options[i].selected = true;
        }
    } else {
    	oServerId.options.length = 0;
    }
}

//初始化服务器列表
function initServerList(area_id, server_id, area_id_field, server_id_field) 
{
	var area_id_field = area_id_field?area_id_field:'area_id';
	var server_id_field = server_id_field?server_id_field:'server_id';
	
    //area_id = !!area_id ? area_id : 1;
	try {
        var oAreaId = _(area_id_field);
        var oServerId = _(server_id_field);
        oAreaId.options.length = 0;
		
		if (area_id_field != 'area_id') oAreaId.options.add(new Option('-- select --', 0));
		
        for (var i = 0; i < serverList.length; i++)
		{
		
            oAreaId.options.add(new Option(serverList[i].name, serverList[i].id));
            if (area_id == serverList[i].id) oAreaId.options[i].selected = true;
        }

        areaChanged(area_id, server_id, area_id_field, server_id_field);
        
    }
    catch(e) {}
}

//获取到游戏的区服列表
function selGame(game_id, area_id, server_id, game_id_field, area_id_field, server_id_field) 
{
	var game_id_field = game_id_field?game_id_field:'game_id';
	var area_id_field = area_id_field?area_id_field:'area_id';
	var server_id_field = server_id_field?server_id_field:'server_id';
	
    $('#'+game_id_field).val(game_id);
    // http://en.login.activity.99.com/enjs/js/serverlist119.js
	$.getScript('http://en.login.activity.99.com/enjs/js/serverlist' + game_id + '.js', function(data) {
		if (game_id == 0) serverList = '';
		initServerList(area_id, server_id, area_id_field, server_id_field);
	});
}

$(document).ready(function(){
	selGame(3);
	selGame(3,'','','','area_id2','server_id2');
});
