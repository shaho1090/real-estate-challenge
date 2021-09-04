<?php

/*
 * This file is for accessing HERE website api key generated for our account.
 * in order to using routing services
 *
 */

return [

    'token' => env('ROUTING_API_HERE_KEY'),

    'address' => 'https://router.hereapi.com/v8/routes'
];
