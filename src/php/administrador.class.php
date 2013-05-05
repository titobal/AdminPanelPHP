<?php
    include("edb.class.php");
    include("validaciones.class.php");
    class Administrador{
        private $id;
        private $correo;
        private $contrasena;
        private $ultimaSesion;
        private $nivel;
        private $estado;
        private $q;
        private $v;
        
        public function __construct(){
            $this->q = new edb();
            $this->v = new Validacion();
        }
        
        public function updateAttr($id, $m){
            $ret = array("err"=>"0","id"=>"0","msg"=>"0","obj"=>"");
            if($this->v->validaNumero($id)){
                if($id == $_SESSION["adm"]){
                    $ret["err"]="1";$ret["msg"]="No puede modificar sus propios atributos.";
                }
                else{
                    if((int)$this->getEstado() == 1){
                        if((int)$this->getNivel() == 0){
                            switch((int)$m){
                                case 1://estado
                                    $this->q->s("UPDATE Administrador SET Estado = CASE WHEN Estado = 1 THEN 0 ELSE 1 END WHERE Id = $id;");
                                    $ret["err"]="0";$ret["msg"]="ok";$ret["obj"]=$this->getAdministrador($id);
                                break;
                                case 2://nivel
                                    $this->q->s("UPDATE Administrador SET Nivel = CASE WHEN Nivel = 1 THEN 0 ELSE 1 END WHERE Id = $id;");
                                    $ret["err"]="0";$ret["msg"]="ok";$ret["obj"]=$this->getAdministrador($id);
                                break;
                                case 3://delete
                                    $this->q->s("DELETE FROM Administrador WHERE Id = $id AND Nivel = 1;");
                                    if($this->q->affectedRows == 1){
                                        $ret["err"]="0";$ret["msg"]="ok";$ret["obj"]=array("Id"=>"$id");
                                    }
                                    else{
                                        $ret["err"]="1";$ret["msg"]="Asegurese de que el aministrador no es Super Administrador.";
                                    }
                                break;
                                default:
                                    $ret["err"]="1";$ret["msg"]="Valor erroneo.";
                                break;
                            }
                        }
                        else{
                           $ret["err"]="1";$ret["msg"]="No tiene permisos suficientes para realizar esta acci&oacute;n."; 
                        }
                    }
                    else{
                        $ret["err"]="1";$ret["msg"]="No est&aacute; autorizado a realizar esta acci&oacute;n.";
                    }
                }
            }
            else{
                $ret["err"]="1";$ret["msg"]="Valor erroneo.";
            }
            return $ret;
        }
        
        public function guardaCodigo($correo, $code){
            $ret = array("err"=>"0", "correo"=>"", "code"=>"", "msg"=> "");
            if($this->setCorreo($correo)){$ret["correo"] = "ok";}
                else{$ret["err"] = "1"; $ret["msg"] = "Correo Incorrecto."; $ret["correo"] = "bad";return $ret;}
            if($this->v->validaTextoNumeroSU($code,30,30)){$ret["code"] = "ok";}
                else{$ret["err"] = "1"; $ret["msg"] = "C&oacute;digo incorrecto."; $ret["pass"] = "bad";return $ret;}
            $r = $this->getCountAdministradorPorCorreo($this->correo);
            if((int)$r == 1){
                $r = $this->q->q("SELECT * FROM Administrador WHERE Correo = '$this->correo';");
                $this->q->s("DELETE FROM CodigoRecuperacion WHERE Objeto = 0 AND Id = ".$r[0]["Id"].";");
                $this->q->s("INSERT INTO CodigoRecuperacion VALUES ('$code',sysdate(),".$r[0]["Id"].",0);");
                if($this->q->affectedRows == 1){
                    $ret["err"] = "0";$ret["msg"]="ok";return $ret;
                }else{$ret["err"]="1";$ret["msg"]="Ha ocurrido un error inesperado al intentar ejecutar la operaci&oacute;n, por favor vuelva a intentarlo m&aacute;s tarde.";return $ret; }
            }else{$ret["err"] = "1"; $ret["msg"] = "Parece que el correo no est&aacute; registrado en la base de datos."; $ret["correo"] = "bad";return $ret;}
            return $ret;
        }
        
        public function newPassword($code, $pass){
            $ret = array("err"=>"0","msg"=>"","code"=>"","pass"=>"");
            if($this->setContrasena($pass)){$ret["pass"]="ok";}else{$ret["err"]="1";$ret["pass"]="bad";$ret["msg"]="Al parecer la contrase&ntilde;a no es v&aacute;lida.";return ret;}
            if($this->v->validaTextoNumeroSU($code,30,30)){$ret["code"]="ok";}else{$ret["err"]="1";$ret["code"]="bad";$ret["msg"]="Al parecer el c&oacute;digo no es v&aacute;lido.";return $ret;}
            $r = $this->q->q("SELECT * FROM CodigoRecuperacion WHERE Codigo = '$code' AND Objeto = 0;");
            if(count($r) === 1){
                $this->q->s("UPDATE Administrador SET Contrasena = '$this->contrasena' WHERE Id = ".$r[0]["Id"].";");
                if($this->q->affectedRows === 1){
                    $this->q->s("DELETE FROM CodigoRecuperacion WHERE Objeto = 0 AND Id = ".$r[0]["Id"].";");
                    $ret["err"]="0";$ret["msg"]="ok";return $ret;
                }else{
                    $ret["err"]="1";$ret["msg"]="Ha ocurrido un error inesperado, por favor vuelva a intentarlo m&aacute;s tarde, y asegurese de que <strong>la contraseña</strong> establecida no es <strong>igual a la anterior</strong>.";return $ret;
                }
            }else{
                $ret["code"]="not found";$ret["err"]="1";$ret["msg"]="Al parecer el c&oacute;digo no es v&aacute;lido.";return $ret;
            }
            return $ret;
        }
        
        public function compCode($code){
            $ret = array("err"=>"0","msg"=>"","code"=>"","form"=>"");
            if($this->v->validaTextoNumeroSU($code, 30, 30)){
                $r = $this->q->q("SELECT COUNT(*) FROM CodigoRecuperacion WHERE Codigo = '$code';");
                if((int)$r[0][0]==1){
                    $ret["err"]="0";$ret["msg"]="ok";$ret["form"]='<fieldset><div class="control-group"><label class="control-label">Nueva Contraseña</label><div class="controls"><input type="password" name="pass1" class="input-xlarge" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required placeholder="8 caracteres mínimo" title="Letras mayusculas y minusculas, un número o simbolo, 8 caracteres minimo."/></div></div><div class="control-group"><label class="control-label">Confirmar Contraseña</label><div class="controls"><input type="password" name="pass2" class="input-xlarge" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required placeholder="8 caracteres mínimo" title="Letras mayusculas y minusculas, un número o simbolo, 8 caracteres minimo."/></div></div><p><small>Contraseña segura, debe ser una combinación de letras mayusculas, letras minusculas, con números o algún simbolo, y de largo mínimo de 8 caracteres.</small></p></fieldset>';
                }else{$ret["err"]="1";$ret["msg"]="El c&oacute;digo ingresado no es v&aacute;lido, puede solicitar uno nuevo";}
            }else{$ret["err"]="1";$ret["msg"]="El c&oacute;digo ingresado no es v&aacute;lido, puede solicitar uno nuevo";}
            return $ret;
        }
        
        public function iniciaSesion($correo, $pass){
            $ret = array("err"=>"0", "correo"=>"", "pass"=>"", "msg"=> "","obj"=>"");
            if($this->setCorreo($correo)){$ret["correo"] = "ok";}
                else{$ret["err"] = "1"; $ret["msg"] = "Correo Incorrecto."; $ret["correo"] = "bad";}
            if($this->setContrasena($pass)){$ret["pass"] = "ok";}
                else{$ret["err"] = "1"; $ret["msg"] = "Contrase&nacute;a incorrecta."; $ret["pass"] = "bad";}
            if($ret["err"] == "0"){
                $i = $this->intentaIniciarSesion();
                if((int)$i != 0){
                    $_SESSION["adm"] = $i;
                    $this->updateUltimaSesion($i);
                    $ret["err"] = "0";$ret["msg"] = "ok";
                }
                else{
                    $ret["err"] = "2";$ret["msg"] = "Usuario o contrase&ntilde;a incorrectos.";
                }
            }
            return $ret;
        }
        
        public function nuevoAdministrador($correo, $nivel){
            $ret = array("err"=>"0", "correo"=>"", "nivel"=>"", "msg"=>"", "obj"=>"");
            if($this->setCorreo($correo)){$ret["correo"] = "ok";}
                else{$ret["err"] = "1"; $ret["msg"] = "Correo Incorrecto"; $ret["correo"] = "bad";}
            if($this->setNivel($nivel)){$ret["nivel"] = "ok";}
                else{$ret["err"] = "1"; $ret["msg"] = "Nivel Incorrecto"; $ret["nivel"] = "bad";}
            if($ret["err"] == "0"){
                $i = $this->getCountAdministradorPorCorreo();
                if($i == "0"){
                    $this->q->s("INSERT INTO Administrador VALUES (NULL, 1, $this->nivel, '$this->correo', NULL, 'no tiene');");
                    $ret["obj"] = $this->getAdministrador($this->q->lastID());$ret["msg"] = "ok";
                }
                else{
                    $ret["err"] = "2";$ret["msg"] = "Ya existe un administrador con ese correo.";
                }
            }
            return $ret;
        }
        
        private function getNivel($id = null){
            $id = ($id == null)?$_SESSION["adm"]:$id;
            $r = $this->q->q("SELECT Nivel FROM Administrador WHERE Id = $id;");
            return $r[0][0];
        }
        
        private function getEstado(){
            $r = $this->q->q("SELECT Estado FROM Administrador WHERE Id = ".$_SESSION['adm'].";");
            return $r[0][0];
        }        
        
        private function updateUltimaSesion($id){
            $this->q->s("UPDATE Administrador SET UltimaSesion = sysdate() WHERE Id = $id;");
        }
        
        private function intentaIniciarSesion(){
            $r = $this->q->q("SELECT Id FROM Administrador WHERE Correo = '$this->correo' AND Contrasena = '$this->contrasena';");
            if(count($r) == 1){
                return $r[0][0];
            } else { return 0; }
        }
        
        private function getCountAdministradorPorCorreo(){
            $r = $this->q->q("SELECT COUNT(*) FROM Administrador WHERE Correo = '$this->correo';");
            return $r[0][0];
        }
        
        public function getAdministrador($id = false){
            $id = ($id == false) ? $_SESSION['adm'] : $id;
            $r = $this->q->q("SELECT * FROM Administrador WHERE Id = $id;");
            return $r;
        }
        
        public function getAdministradores(){
            $r = $this->q->q("SELECT Id, Estado, Nivel, Correo, UltimaSesion FROM Administrador;");
            return $r;
        }
        
        public function setNivel($nivel){
            if($this->v->validaNumero($nivel,1,1)){
                $this->nivel = $nivel;
                return true;
            }
            else{
                return false;
            }
        }
        
        public function setCorreo($correo){
            $cor = strtolower($correo);
            if($this->v->validarMail($cor)){
                $this->correo = $cor;
                return true;
            }
            else{
                return false;
            }
        }
        
        public function setContrasena($pass){
            if($this->v->validaTextoNumeroSU($pass,55,55)){
                $this->contrasena = $pass;
                return true;
            }
            else{
                return false;
            }
        }
    }
?>
