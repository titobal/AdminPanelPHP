<?php
    session_start();
    if(isset($_SESSION["adm"])){
        include("../src/php/messages.class.php");
        if(isset($_POST["o"])){
            switch((int)$_POST["o"]){
                case 3://ADMINISTRADOR
                    if(isset($_POST["m"])){
                        include("../src/php/administrador.class.php");
                        $a = new Administrador();
                        switch((int)$_POST["m"]){
                            case 1:
                                $b = $a->getAdministradores();
                                $r = array("err"=>"0");
                                array_push($r, $b);
                                echo json_encode($r);
                            break;
                            case 2:
                                if(isset($_POST["correo"], $_POST["nivel"])){
                                    $r = $a->nuevoAdministrador($_POST["correo"], $_POST["nivel"]);
                                    echo json_encode($r);
                                }
                                else{
                                    Messages::sinValores();
                                }
                            break;
                            case 3:
                                if(isset($_POST["id"], $_POST["t"])){
                                    $r = $a->updateAttr($_POST["id"], $_POST["t"]);
                                    echo json_encode($r);
                                }
                                else{
                                    Messages::sinValores();
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
    else{
        header("Location: src/../");
    }
?>