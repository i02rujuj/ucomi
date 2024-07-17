<?php 

return [ 
    'REPRESENTACIONES' => [
        'GOBIERNO'=> [
            'DIR' => 1, 
            'DEC' => 2,
            'SUBDIR' => 3, 
            'VICEDEC' => 4, 
            'SECRE' => 5, 
            'LIBRE' => 10,
        ],
        'JUNTA'=> [
            'DIR' => 1, 
            'DEC' => 2,
            'SECRE' => 5,
            'PDI_VP' => 6, 
            'PDI' => 7, 
            'PAS' => 8, 
            'EST' => 9, 
            'LIBRE' => 10, 
        ],
        'COMISION'=> [
            'DIR' => 1, 
            'SECRE' => 5,
            'PDI_VP' => 6, 
            'PDI' => 7, 
            'PAS' => 8, 
            'EST' => 9, 
        ],
    ],
    'TIPOS_CENTRO' => [
        'FACULTAD' => 1, 
        'ESCUELA' => 2,
        'OTRO' => 3,
    ],
    'TIPOS_CONVOCATORIA' => [
        'ORDINARIA' => 1, 
        'EXTRAORDINARIA' => 2,
        'URGENTE' => 3,
    ],
];


