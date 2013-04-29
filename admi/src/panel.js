var Controla = function(){
    this.v = {i4:""};
    this.ajaxSetup = function(){
        $.ajaxSetup({url:"fpanel.php",type:"POST"});
    };
    this.pnotify = function(){
        if($.pnotify.defaults.delay){$.pnotify.defaults.delay = 4000;}
        $.pnotify.defaults.history = false;
    };
    this.tabs = function(){
        $("a[href='#Administradores']").click(function(){
            admin.getAdministradores();
            $(this).unbind("click");
        });
    };
    this.btUpdate = function(){
        $("#Administradores .bt-update").click(function(){
            admin.getAdministradores();
        });
    };
    this.updNames = function(){
        var me = this;
        this.v.i4 = tools.randomString(5);
        $("#4").attr("id", me.v.i4);
        me.v.i4 = "#" + me.v.i4;
    };
    this.confirm = function(text){
        $("#confirm").modal("show").find("p").html(text);
        $("#confirm .true").focus();
    };
};

var c = new Controla();

$(document).ready(function(){
    c.ajaxSetup();
    c.pnotify();
    c.tabs();
    c.btUpdate();
    c.updNames();
    admin.showFNewAdmin();
    if(admin.adapta){admin.adapta()}
}).bind("ajaxSend", tools.ajaxStart).bind("ajaxStop", tools.ajaxFinish);