<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Payflow API',
    description: 'API de suporte à gestão, visualização e acompanhamento de pagamentos.'
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local environment'
)]
class OpenApiSpec
{
}
