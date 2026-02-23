<?php
Class Estados {
    public static function estados_usuarios($estado){
        $estados= [
            "1"=>'<span class="m-badge m-badge--warning m-badge--wide">Pendiente</span>',
        ];
        return $estados [$estado];
    }
    public static function estados_tramite($estado){
        $estados= [
            "1"=>'<span class="m-badge m-badge--warning m-badge--wide">Pendiente</span>',
        ];
        return $estados [$estado];
    }
}
?>