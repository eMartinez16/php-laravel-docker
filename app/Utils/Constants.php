<?php

namespace App\Utils;

class Constants {
    
    const PORTS = [
        'LDC'      => 'LDC Argentina S.A',
        'CARGILL'  => 'Cargill S.A.C.I',
        'ADM'      => 'ADM Agro SRL',
        'TERMINAL' => 'Terminal Bahía Blanca S.A',
        'VITERRA'  => 'Viterra Argentina S.A'
    ];

    const CONDITIONS = [
        'Grado 1',
        'Grado 2',
        'Grado 3',
        'Cond. Cámara'
    ];

    const CERT_PATH = DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Actions'.DIRECTORY_SEPARATOR.'afipsdk/afip.php/src/Afip_res/CERT/'; 
    CONST TA_PATH   = DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Actions'.DIRECTORY_SEPARATOR.'afipsdk/afip.php/src/Afip_res/TA/';


    public static function getPorts()
    {
        return self::PORTS;
    }

    public static function getConditions()
    {
        return self::CONDITIONS;
    }

    public static function getTAPath(){
        return self::TA_PATH;
    }

    public static function getCERTPath(){
        return self::CERT_PATH;
    }

}











