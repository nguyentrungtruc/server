<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials'    => true,
    'allowedOrigins'         => ['https://maps.googleapis.com/', 'http://dofuu.com', 'http://localhost:3333'],
    'allowedOriginsPatterns' => [],
    'allowedHeaders'         => ['*','Content-Type', 'X-Requested-With', 'authorization'],
    'allowedMethods'         => ['*'],
    'exposedHeaders'         => [],
    'maxAge'                 => 0,
];
