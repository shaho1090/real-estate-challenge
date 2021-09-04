<?php

/*
 * This file is for accessing HERE website api key generated for our account.
 * in order to using routing services
 *
 */

return [

    'token' => env('HERE_ROUTING_API_KEY'),

    'address' => 'https://router.hereapi.com/v8/routes'
];
