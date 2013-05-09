var Tools = function(){
    this.errText = {
        noCom : "No se ha logrado establecer la conexión con el servidor, revise su conectividad a internet y vuelva a intentarlo nuevamene más tarde.",
        erIne : "Ha ocurrido un error inesperado mientras se ejecutaba la operación, por favor vuelva a intentarlo más tarde."
    };
    this.pnotify ={
        bottomright : function(title, msg, type){
            var opts = {
                title: title,
                text: msg,
                type: type,
                nonblock: true
            };
            $.pnotify(opts);
        }
    };
    this.ajaxStart = function(){
        $("#loading").fadeIn(200);
    };
    this.ajaxFinish = function(){
        $("#loading").fadeOut(200);
    };
    this.fTemplate = _.template('<div class="modal-header">'+
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
                '<h3><%= title %></h3>'+
            '</div>'+
            '<form id="<%= id %>" style="margin:0">'+
                '<div class="modal-body"><%= form %></div>'+
                '<div class="modal-footer">'+
                    '<a href="#" role="button" data-dismiss="modal" class="btn">Cerrar</a>'+
                    '<button class="btn btn-primary">Guardar</button>'+
                '</div>'+
            '</form>');
    this.randomString = function(largo){
        var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = largo;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
    };
    this.returnIndex = function(items, f){
        for(var i in  items) {
          var item = items[i];
          if (f(item)) return i;
        };
        return "undefined";
    };
    this.msg = {
        errIne : function(){tools.pnotify.bottomright("Error", tools.errText.erIne, "error");},
        msg : function(msg){tools.pnotify.bottomright("Error", msg, "error");},
        noCon : function(){tools.pnotify.bottomright("Error", tools.errText.noCom, "error");}
    };
    this.ajaxDone = function(data, fun, funb){
        console.log(data);
        try{
            var d = JSON.parse(data);
            if(d.err === "0"){
                if(typeof fun === "function"){
                    fun(d);
                }
            }
            else{
                if(typeof funb === "function"){
                    funb(d);
                }
                tools.msg.msg(d.msg);
            }
        }
        catch(e){
            console.log(e);
            tools.msg.errIne();
        }
    };
    this.ajaxFail = function(){
        tools.msg.noCon();
    };
    this.printDate = function(d){
        var temp = d.split(" ");
        return temp[0].split("-").reverse().join('-') + " " + temp[1];
    };
};

var tools = new Tools();
