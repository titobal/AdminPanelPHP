<?php
    class Validacion{
        public function __construct() {}
        
        public function validaTexto($texto, $min, $max){
            if($this->validarAlfabetico($this->unicodeString($texto))
                    && $this->longitudMayorIgualQue($texto, $min)
                    && $this->longitudMenorIgualQue($texto, $max)){
                return true;
            }
            return false;
        }
        
        public function validaTextoNumero($texto, $min, $max){
            if($this->validarAlfaNumerico($this->unicodeString($texto))
                    && $this->longitudMayorIgualQue($texto, $min)
                    && $this->longitudMenorIgualQue($texto, $max)){
                return true;
            }
            return false;
        }
        
        public function validaTextoNumeroSU($texto, $min, $max){
            if($this->validarAlfaNumerico($texto)
                    && $this->longitudMayorIgualQue($texto, $min)
                    && $this->longitudMenorIgualQue($texto, $max)){
                return true;
            }
            return false;
        }
        
        public function validaNumero($num, $min = null, $max = null){
            if($this->ValidarDigitos($num)
                    && ($min != null) ? $this->longitudMayorIgualQue($num, $min) : true
                    && ($max != null) ? $this->longitudMenorIgualQue($num, $max) : true){
                return true;
            }
            return false;
        }
        
        public function short($ret, $column, $msg, $vali, $value, $min=null, $max=null){
            $test = false;
            switch($vali){
                case "validaTexto":
                    $test = $this->validaTexto($value, $min, $max);
                break;
                case "validaTextoNumero":
                    $test = $this->validaTextoNumero($value, $min, $max);
                break;
                case "validaTextoNumeroSU":
                    $test = $this->validaTextoNumeroSU($value, $min, $max);
                break;
                case "validaNumero":
                    $test = $this->validaNumero($value, $min, $max);
                break;
                case "validarMail":
                    $test = $this->validarMail($value);
                break;
            }
            if($test){
                $ret[$column] = "ok";
            }else{
                $ret[$column] = "bad";
                $ret["err"] = "1";
                $ret["msg"] = $msg;
            }
            return $ret;
        }
        
        //validar un mail
        public function validarMail($valor){
            if(filter_var($valor, FILTER_VALIDATE_EMAIL) === false){return false;}
            return true;
        }
        
        //validar un float
        private function validarFloat($valor){
            if(filter_var($valor, FILTER_VALIDATE_FLOAT) === false){return false;}
            return true;
        }
        
        //validar un entero
        private function validarEntero($valor){
            if(filter_var($valor, FILTER_VALIDATE_INT) === false){return false;}
            return true;
        }
        
        //validar una fecha
        private function validarFecha($valor){
              if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $valor)){return true;}
              else{return false;}
        }
        
        //validar un texto alfabético
        private function validarAlfabetico($valor){
              if (preg_match('|^[a-zA-Z ñÑáéíóú]*$|', $valor)){return true;}
              else{return false;}
        }
        
        //validar un texto alfabético
        private function validarAlfaNumerico($valor){
              if (preg_match('|^[a-zA-Z\d ñÑáéíóú]*$|', $valor)){return true;}
              else{return false;}
        }
        
        //validar un teléfono (sólo si son 9 dígitos)
        public function validarTelefono($valor){
              if (preg_match('|^\d{8,9}$|', $valor)){return true;}
              else{return false;}
        }
        
        //validar que una cadena sólo tenga dígitos
        private function validarDigitos($valor){
              if (preg_match('|^[0-9.]*$|', $valor)){return true;}
              else{return false;}
        }
        
        //comprobamos que la longitud de una cadena es menor a la indicada por parámetro
        private function longitudMenorIgualQue($valor,$longitud){
            if(strlen($valor)<=$longitud) {return true;}
            return false;
        }
        
        //comprobamos que la longitud de una cadena es mayor o igual a la indicada por parámetro
        private function longitudMayorIgualQue($valor,$longitud){
            if(strlen($valor)>=$longitud) {return true;}
            return false;
        }
        
        private function unicodeString($str, $encoding=null) {
            if (is_null($encoding)) $encoding = ini_get('mbstring.internal_encoding');
            return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), '.var_export($encoding, true).', "UTF-16BE");'), $str);
        }
    }
?>
