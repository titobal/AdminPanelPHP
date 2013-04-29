<?php
    session_start();
    if(isset($_SESSION["adm"])){
        if(isset($_POST["f"])){
            switch((int)$_POST["f"]){
                case 1:
                    ?>

                    <?php
                break;
                default:
                    echo '{"err":"0","msg":"No se ha definido el tipo de operaci&oacute;n."}'; 
                break;
            }
        }
        else{
           echo '{"err":"0","msg":"No se han establecido valores para realizar la operaci&oacute;n."}'; 
        }
    }
    else{
        echo '{"err":"0","0":"0"}';
    }
?>