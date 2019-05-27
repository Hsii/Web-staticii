window.base={
    g_restUrl:'http://hmapi.com/api/v1/',

    getData:function(params){
        if(!params.type){
            params.type='get';
        }
        var that=this;
        $.ajax({
            type:params.type,
            url:this.g_restUrl+params.url,
            data:params.data,
            beforeSend: function (XMLHttpRequest) {
                if (params.tokenFlag) {
                    XMLHttpRequest.setRequestHeader('token', that.getLocalStorage('token'));
                }
            },
            success:function(res){
                params.sCallback && params.sCallback(res);
            },
            error:function(res){
                params.eCallback && params.eCallback(res);
            }
        });
    },

    setLocalStorage:function(key,val){
        var exp=new Date().getTime()+2*24*60*60*100;  //令牌过期时间
        var obj={
            val:val,
            exp:exp
        };
        localStorage.setItem(key,JSON.stringify(obj));
    },

    getLocalStorage:function(key){
        var info= localStorage.getItem(key);
        if(info) {
            info = JSON.parse(info);
            if (info.exp > new Date().getTime()) {
                return info.val;
            }
            else{
                this.deleteLocalStorage('token');
            }
        }
        return '';
    },

    deleteLocalStorage:function(key){
        return localStorage.removeItem(key);
    }

};
layui.use('layer', function () {
    var $ = layui.jquery, layer = layui.layer;
    //layui_open
});
function pe_dialog(_this, _title, width, height) {
    var url = (typeof(_this) == 'object') ? $(_this).attr("href") : _this;
    var layer_index = layer.open({
        type: 2,
        title: _title,
        area: [width + 'px', height + 'px'],
        fixed: false, //不固定
        shadeClose: true,
        shade: 0.5,
        content: url //iframe的url
    });
    if (width == 'max' && height == 'max') layer.full(layer_index);
    return false;
}