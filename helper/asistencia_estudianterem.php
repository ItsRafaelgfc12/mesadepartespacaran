<?php
class AsistenciaEstudiante
{
    public static function asistencia_estudiante($tipo){
        $asistencias = [
            "A"=>"<span class='badge badge-pill badge-success'>Asistió</span>",
            "T"=>"<span class='badge badge-pill badge-warning'>Tardanza</span>",
            "F"=>"<span class='badge badge-pill badge-danger'>Faltó</span>",
            "J"=>"<span class='badge badge-pill badge-info'>Falta Justificada</span>",
        ];
        return $asistencias[$tipo];
    }
    public static function texto($tipo){
        $asistencias = [
            "A"=>"Asistió",
            "T"=>"Tardanza",
            "F"=>"Faltó",
            "J"=>"Falta Justificada",
        ];
        return $asistencias[$tipo];
    }   
}
