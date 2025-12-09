<?php
class Estados
{
    public static function estados_solicitud_certificado($estado){
        $estados = [
            "1"=>'<span class="m-badge m-badge--warning m-badge--wide">Pendiente</span>',//10%
            "2"=>'<span class="m-badge m-badge--danger m-badge--wide">Observado</span>',//15%
            "3"=>'<span class="m-badge m-badge--accent m-badge--wide">Verificado</span>',//30%
            "4"=>'<span class="m-badge m-badge--primary m-badge--wide">En tramite</span>',//80%
            "5"=>'<span class="m-badge m-badge--brand m-badge--wide">Completado</span>',//100%
            "6"=>'<span class="m-badge m-badge--success m-badge--wide">Entregado</span>'//100%
        ];
        return $estados[$estado];
    }
    public static function progreso_solicitud_certificado($estado){
        $estados = [
            "1" => 10,
            "2" => 15,
            "3" => 30,
            "4" => 80,
            "5" => 100,
            "6" => 100,
        ];
        return $estados[$estado];
    }
    public static function estados_examen($estado) {
        $estados = [
            2=>'<span class="m-badge m-badge--warning m-badge--wide">Pendiente</span>',
            3=>'<span class="m-badge m-badge--danger m-badge--wide">Rechazado</span>',
            1=>'<span class="m-badge m-badge--success m-badge--wide">Verificado</span>',
            4=>'<span class="m-badge m-badge--primary m-badge--wide">Modificado</span>',
        ];
        return $estados[$estado];
    }
    public static function estados_matricula($estado){
        $estados = [
            "2"=>'<span class="m-badge m-badge--warning m-badge--wide">Pendiente</span>',
            "3"=>'<span class="m-badge m-badge--danger m-badge--wide">Rechazado</span>',
            "1"=>'<span class="m-badge m-badge--success m-badge--wide">Verificado</span>',
            "4"=>'<span class="m-badge m-badge--primary m-badge--wide">Modificado</span>'
        ];
        return $estados[$estado];
    }
    public static function estados_constancia($estado){
        $estados = [
            "Pendiente"=>'<span class="m-badge m-badge--secondary m-badge--wide">Pendiente</span>',
            "Verificado"=>'<span class="m-badge m-badge--success m-badge--wide">Verificado</span>',
            "Observado"=>'<span class="m-badge m-badge--danger m-badge--wide">Observado</span>',
            "En tramite"=>'<span class="m-badge m-badge--warning m-badge--wide">En tramite</span>',
            "Entregado"=>'<span class="m-badge m-badge--primary m-badge--wide">Entregado</span>'
        ];
        return $estados[$estado];
    }
    public static function nuevo_estado_examen($estado){
        $estados = [
            'Activo' =>'<span class="m-badge m-badge--success m-badge--wide">Activo</span>',
            'Inactivo' =>'<span class="m-badge m-badge--warning m-badge--wide">Inactivo</span>',
        ];
        return $estados[$estado];
    }
}