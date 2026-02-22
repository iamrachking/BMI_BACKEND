<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => env('L5_SWAGGER_TITLE', 'API AI4BMI'),
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'annotations' => [
                    base_path('app/Http/Controllers/Api'),
                    base_path('app/OpenApi'),
                ],
            ],
            'defaults' => [
                'routes' => [
                    'docs' => 'docs',
                    'oauth2_callback' => 'api/oauth2-callback',
                    'middleware' => ['api' => [], 'asset' => [], 'docs' => [], 'oauth2_callback' => []],
                    'group_options' => [],
                ],
                'paths' => [
                    'docs' => storage_path('api-docs'),
                    'views' => base_path('resources/views/vendor/l5-swagger'),
                    'base' => env('L5_SWAGGER_BASE_PATH', null),
                    'excludes' => [],
                ],
                'scanOptions' => [
                    'analyser' => null,
                    'analysis' => null,
                    'processors' => [],
                    'pattern' => null,
                    'exclude' => [],
                ],
                'securityDefinitions' => [
                    'securitySchemes' => [
                        'sanctum' => [
                            'type' => 'http',
                            'description' => 'Token Bearer (POST /api/login)',
                            'name' => 'Authorization',
                            'in' => 'header',
                            'scheme' => 'bearer',
                            'bearerFormat' => 'JWT',
                        ],
                    ],
                    'security' => [['sanctum' => []]],
                ],
                'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
                'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
                'proxy' => false,
                'operations_sort' => null,
                'validator_url' => null,
                'ui' => [
                    'display' => [
                        'dark_mode' => false,
                        'doc_expansion' => 'list',
                        'filter' => true,
                    ],
                    'authorization' => ['persist_authorization' => true],
                ],
            ],
        ],
    ],
    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', env('APP_URL', 'http://localhost')),
    ],
];
