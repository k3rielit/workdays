<?php

return [
    'workspace_id' => env('CLOCKIFY_WORKSPACE_ID'),
    'project_id' => env('CLOCKIFY_PROJECT_ID'),
    'api_token' => env('CLOCKIFY_API_TOKEN'),
    'url' => env('CLOCKIFY_URL', 'https://api.clockify.me/api'),
];
