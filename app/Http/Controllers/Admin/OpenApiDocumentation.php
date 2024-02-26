<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class OpenApiDocumentation extends Controller
{
    public function index()
    {
        return view('api.documentation.index');
    }

    public function openApiSpec()
    {
        return response()->download(base_path('docs/openapi_spec/swagger.yaml'), 'swagger.yaml');
    }
}
