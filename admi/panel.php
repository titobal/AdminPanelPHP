<?php
    if(session_status() != PHP_SESSION_ACTIVE){session_start();}
    if(!isset($_SESSION['adm'])){
        header('Location: src/../');
    }
    include("../src/php/administrador.class.php");
    $ad = new Administrador();
    $a = $ad->getAdministrador();
    $a = $a[0];
?>
<!doctype html>
<html>
    <head>
        <title>Inicio de sesi&oacute;n</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="../src/js/jquery.min.js" type="text/javascript"></script>
        <script src="../src/js/underscore.min.js" type="text/javascript"></script>
        <script src="../src/bootstrap/pnotify/jquery.pnotify.min.js" type="text/javascript"></script>
        <script src="../src/js/tools.js" type="text/javascript"></script>
        <script src="src/administrador<?php echo $a['Nivel'] == '0'?'-super':''; ?>.js" type="text/javascript"></script>
        <script src="src/panel.js" type="text/javascript"></script>
        <link href="../src/bootstrap/css/united.bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="../src/bootstrap/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet"/>
        <link href="../src/bootstrap/modal/css/bootstrap-modal.css" type="text/css" rel="stylesheet"/>
        <link href="../src/bootstrap/css/docs.css" type="text/css" rel="stylesheet"/>
        <link href="../src/bootstrap/pnotify/jquery.pnotify.default.css" type="text/css" rel="stylesheet"/>
        <link href="../src/bootstrap/pnotify/useicons/jquery.pnotify.default.icons.css" type="text/css" rel="stylesheet"/>
        <link href="../src/bootstrap/css/animate-custom.min.css" type="text/css" rel="stylesheet"/>
        <link href="src/panel.css" type="text/css" rel="stylesheet"/>
    </head>
    <body class="preview" style="background-image: url('../images/shattered.png');background-repeat: repeat;">
        <!--header-->
        <div style="display:none" class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a style="color:#fff" class="brand" href="">Administrador</a>
                    <div class="nav-collapse collapse" id="main-menu" style="height: 0px;">
                        <ul class="nav" id="main-menu-left">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $a["Correo"]; ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu" id="swatch-menu">
                                    <li><a href="logout.php">Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!--container-->
        <div style="display:none" class="container main">
            <div class="row">
                <div class="span3 bs-docs-sidebar">
                    <ul style="top:0" class="nav nav-list bs-docs-sidenav affix">
                        <li class="active"><a href="#Usuarios" data-toggle="tab"><i class="icon-chevron-right"></i> Usuarios</a></li>
                        <li class=""><a href="#Eventos" data-toggle="tab"><i class="icon-chevron-right"></i> Eventos</a></li>
                        <li class=""><a href="#Administradores" data-toggle="tab"><i class="icon-chevron-right"></i> Administradores</a></li>
                    </ul>
                </div>
                <div class="span9 tab-content">
                    <br/>
                    <div id="Usuarios" class="tab-pane animated fadeInDown in active">
                        <h1>Usuarios</h1>
                    </div>
                    <div id="Eventos" class="tab-pane animated fadeInDown">
                        <h1>Eventos</h1>
                    </div>
                    <div id="Administradores" class="tab-pane animated fadeInDown">
                        <h1>Administradores</h1>
                        <?php
                            if($a['Nivel'] == '0'){
                                echo '<div class="btn-group">
                                    <button class="btn btn-primary bt-update"><i class="icon-white icon-refresh"></i></button>
                                    <button class="btn btn-primary bt-new"><i class="icon-white icon-plus"></i></button>
                                </div>';
                            }
                            else{
                                echo '<button class="btn btn-primary bt-update"><i class="icon-white icon-refresh"></i></button>';
                            }
                        ?>
                        <br/><br/>
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Correo</th>
                                    <th>Nivel</th>
                                    <th>Ultima Sesión</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <script>setInterval(function(){$(".navbar-fixed-top").show().addClass("animated fadeInDown");
        setInterval(function(){$(".main").show().addClass("animated fadeInDown");},600);},1000);</script>
        
        <div id="4" class="modal hide fade"></div>
        
        <div id="loading" class="text-center">
            <div class="progress progress-striped active">
                <div class="bar" style="width: 100%;"></div>
            </div>
            <p>Cargando...</p>
        </div>
        
        <div id="confirm" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-body"><p class="message"></p></div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Cancelar</button>
                <button type="button" class="btn btn-primary true">Aceptar</button>
            </div>
        </div>
        
        <script src="../src/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../src/bootstrap/modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
        <script src="../src/bootstrap/modal/js/bootstrap-modal.js" type="text/javascript"></script>
    </body>
</html>
