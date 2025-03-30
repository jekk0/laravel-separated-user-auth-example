<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', title: 'Laravel Separated User Auth(JWT) Example')]
#[OA\SecurityScheme(securityScheme: 'JWT', type: 'http', name: 'JWT', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
class Controller
{
}
