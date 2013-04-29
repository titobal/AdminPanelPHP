<?php
    session_start();
    include("../src/php/messages.class.php");
    if(isset($_SESSION["adm"])){
        header("Location: src/../");
    }
    else{
        if(isset($_POST["m"])){
            include("../src/php/administrador.class.php");
            $a = new Administrador();
            switch((int)$_POST["m"]){
                case 1:
                    if(isset($_POST["correo"], $_POST["pass"])){
                        $ret = $a->iniciaSesion($_POST["correo"], $_POST["pass"]);
                        echo json_encode($ret);
                    }
                    else{
                        Messages::sinValores();
                    }                    
                break;
                case 2:
                    if(isset($_POST["correo"])){
                        include("../src/php/tools.class.php");
                        $t = new Tools();
                        $ret = $a->guardaCodigo($_POST["correo"], $t->genCode(30));
                        echo json_encode($ret);
                    }
                    else{
                        Messages::sinValores();
                    }
                break;
                case 3:
                    if(isset($_POST["c"])){
                        $r = $a->compCode($_POST["c"]);
                        echo json_encode($r);
                    }
                    else{
                        Messages::peticionIncorrecta();
                    }
                break;
                case 4:
                    if(isset($_POST["code"], $_POST["pass"])){
                        $r = $a->newPassword($_POST["code"], $_POST["pass"]);
                        echo json_encode($r);
                    }
                    else{
                        Messages::peticionIncorrecta();
                    }
                break;
                default:
                    Messages::peticionIncorrecta();
                break;
            }
        }
        else{
            Messages::sinValores();
        }
    }
?>