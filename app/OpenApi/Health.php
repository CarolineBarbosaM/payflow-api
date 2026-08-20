<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/health',
    operationId: 'healthCheck',
    tags: ['Health'],
    summary: 'Health check',
    description: 'Verifica se a API está disponível.',
    responses: [
        new OA\Response(
            response: 200,
            description: 'API disponível',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'ok',
                    ),
                ],
                type: 'object',
            ),
        ),
    ],
)]
class Health
{
}
