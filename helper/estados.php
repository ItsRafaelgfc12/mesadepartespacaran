<?php
Class Estados {
    public static function estados_usuarios($estado){
        $estados= [
            "1"=>'<span class="m-badge m-badge--warning m-badge--wide">Pendiente</span>',//10%
        ];
        return $estados [$estado];
    }
    
}
?>