var Administrador = function(){
    this.administradores = new Array();
    this.table = "<% _.each(as, function(ad){ %> <tr><td><%= ad.Id %></td><td><%= ad.Correo %></td><td><%= ad.Nivel==0?'Super Administrador':'Administrador' %></td><td><%= ad.UltimaSesion == null ? 'No ha iniciado sesión' : ad.UltimaSesion %></td><td><span class='label <%= ad.Estado==1?\"label-success'>Activo\":\"label-warning'>Inactivo\" %></span></td></tr> <% }) %>";
    this.printTable = function(){
        var me = this;
        $("#Administradores table tbody").html(_.template(me.table, {as : me.administradores}));
    };
    this.getAdministradores = function(){
        var me = this;
        $.ajax({
            url:"fpanel.php",
            type:"POST",
            data:{
                o:3,
                m:1
            }
        }).done(function(data){
            try{
                var d = JSON.parse(data);
                if(d.err === "0"){
                    d = d[0];
                    me.administradores = new Array();
                    for(var x in d){
                        me.administradores.push(d[x]);
                    }
                    me.printTable();
                }
                else{
                    tools.pnotify.bottomcenter("Error", d.msg, "error");
                }
            }
            catch(e){
                tools.pnotify.bottomcenter("Error", tools.errText.erIne, "error");
            }
        }).fail(function(){
            tools.pnotify.bottomcenter("Error", tools.errText.noCom, "error");
        });
    };
};

var admin = new Administrador();