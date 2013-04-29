var Submits = function(){
    this.v = {G : {},htmlf:""};
    this.setup = function(){
        var me = this;
        me.consumeAlert();
        $.ajaxSetup({url:"login.php",type:"POST"});
        me.GET();
        me.compCode();
        me.iniSes();
        $("button[href='#modalRec']").click(me.fCreateCode);
    };
    this.mensajes = {"m1":"No se ha logrado la comunicación con el servidor, revise su conexión a internet y vuelva a intentarlo más tarde.",
        "m2":"Ha ocurrido un error inesperado mientras se ejecutaba la operación, por favor vuelva a intentarlo más tarde",
        "m3":"Usuario o contraseña incorrectos.","m4":"Iniciando Sesión."};
    this.consumeAlert = function(){
        window.alert = function(message) {
            $("#alert .modal-body").html(message);
            $("#alert").modal("show");
        };
    };
    this.compCode = function(){
        var me = this;
        if(typeof(me.v.G.c) !== "undefined"){
            $("form button").button("loading");
            if(me.v.G !== "" && me.v.G.c.length === 30){
                $.ajax({data:{m:3,c:me.v.G.c}}).done(function(data){
                    var fun = function(d){
                        s.v.htmlf = d;
                        s.fSetPass();
                        $("#modalRec").modal("show");
                    };
                    var funb = function(){
                        //window.location = "#";
                        s.v.G = {};
                        $("form button").button("reset");
                    };
                    s.ajaxSend(data,fun,funb);
                }).fail(me.ajaxFail);
            }else{
                //window.location = "#";
                s.v.G = {};
                alert("Al parecer el c&oacute;digo no es correcto, puede solicitar uno nuevo.");
            }
            $("form button").button("reset");
        }
    };
    this.subIniSes = function(event){
        (event.preventDefault) ? event.preventDefault() : event.returnValue = false;
        var me = this;
        $("form button").button("loading");
        $("form").eq(0).unbind("submit", me.subIniSes);
        $.ajax({data:{m:1,
                correo:$("form [name='correo']").val(),
                pass:calcSHA1($("form [name='pass']").val())
            }
        }).done(function(data){
            var fun = function(){
                $(".well").attr("class","well animated fadeOutLeft");
                alert(s.mensajes.m4);
                window.location.reload();
            };
            s.ajaxSend(data, fun);
            $("form").eq(0).bind("submit", s.subIniSes);
        }).fail(function(){
            s.ajaxFail();
            $("form").eq(0).bind("submit", s.subIniSes);
        });
    };
    this.ajaxFail = function(){
        alert(s.mensajes.m1);
        $("form button").button("reset");
    };
    this.ajaxSend = function(data, fun, funb){
        console.log(data);
        try{
            var d = JSON.parse(data);
            if(d.err === "0"){
                fun(d.form);
            }
            else{
                alert(d.msg);
                if(typeof(funb) === "function"){
                    funb();
                }
            }
        }
        catch(e){
            console.log(e);
            alert(s.mensajes.m2);
        }
        $("form button").button("reset");
    };
    this.iniSes = function(){
        var me = this;
        $("form").eq(0).bind("submit", me.subIniSes);
    };
    this.ajaxStart = function(){
        $("#loading").slideDown(200);
    };
    this.ajaxFinish = function(){
        $("#loading").slideUp(200);
    };
    this.fSetPass = function(){
        var fun = function(event){
            (event.preventDefault) ? event.preventDefault() : event.returnValue = false;
            var pass1 = $("#modalRec [name='pass1']").val(),pass2 = $("#modalRec [name='pass2']").val();
            if(pass1 === pass2){
                $.ajax({data:{m:4,code:s.v.G.c,pass:calcSHA1(pass1)}}).done(function(data){
                    var func = function(){
                        $("#modalRec").modal("hide");
                        alert("Ya puede utilizar su nueva contraseña, el c&oacute;digo ya no puede ser utilizado nuevamente.");
                    };
                    s.ajaxSend(data, func);
                }).fail(s.ajaxFail);
            }else{alert("Las contraseñas no coinciden.");}
        };
        s.createForm("Nueva contraseña",s.v.htmlf, fun);
    };
    this.fCreateCode = function(){
        if(typeof(s.v.G.c) !== "undefined" && s.v.G.c.length === 30){
            s.fSetPass();
        }
        else{
           var html = '<fieldset><label class="control-label">Correo electrónico</label><div class="controls"><input type="email" name="correo" required placeholder="ejemplo@ejemplo.cl" class="input-xlarge"/></div></fieldset>';
            var fun = function(event){
                (event.preventDefault) ? event.preventDefault() : event.returnValue = false;
                $("form button").button("loading");
                $.ajax({data:{m:2,correo:$("#modalRec [name='correo']").val()}}).done(function(data){
                    var func = function(){
                        $("#modalRec").modal("hide");
                        alert("Se ha enviado un codigo de verificación a su dirección de correo electrónico, revíselo para continuar.");
                    };
                    s.ajaxSend(data, func);
                }).fail(s.ajaxFail);
            };
            s.createForm("Recuperar contraseña",html, fun); 
        }
    };
    this.createForm = function(title, text, fun){
        var html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3>'+title+'</h3></div><form style="margin:0" class="form-horizontal"><div class="modal-body text-center">'+text+'</div><div class="modal-footer"><button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button><button class="btn btn-primary">Recuperar</button></div></form>';
        $("#modalRec").html(html).find("form").submit(fun);    
    };
    this.GET = function(){
        var me = this;
        if(window.location.hash !== ""){
            var parts = window.location.hash.substr(1).split("/");
            me.v.G = {};
            var temp;
            for (var i in parts) {
                temp = parts[i].split("=");
                me.v.G[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
            }
        }
    };
};

var s = new Submits();

$(document).ready(function(){
    s.setup();
}).bind("ajaxSend", s.ajaxStart).bind("ajaxStop", s.ajaxFinish);;