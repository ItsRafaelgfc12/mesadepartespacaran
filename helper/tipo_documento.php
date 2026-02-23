<?php
Class TipoDocumento {
    public static function tipo_documento($tipodoc){
        $tipodoc= [
            "1"=>'<span">Pendiente</span>',
            "2"=>'<span">Carné de Extranjería</span>',
            "3"=>'<span">Pasaporte</span>',
        ];
        return $tipodoc[$tipodoc];
    }
    
}
?>