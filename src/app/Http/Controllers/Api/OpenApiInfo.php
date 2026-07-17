<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API для формы обратной связи',
    title: 'Landing Presentation API',
    contact: new OA\Contact(email: 'support@example.com'),
    license: new OA\License(
        name: 'Apache 2.0',
        url: 'http://www.apache.org/licenses/LICENSE-2.0.html'
    )
)]
#[OA\Server(
    url: 'http://localhost:8080',
    description: 'API Server'
)]
class OpenApiInfo
{
}
