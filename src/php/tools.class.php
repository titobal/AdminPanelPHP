<?php
    class Tools{
        public function __construct(){}
        
        static function genCode($l){
            $key = '';
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $max = strlen($pattern)-1;
            for($i=0;$i < $l;$i++) $key .= $pattern{mt_rand(0,$max)};
            return $key;
        }
    }
?>