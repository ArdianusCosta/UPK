<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "API Tugas UKK Peminjaman Alat",
    version: "1.0.0",
    description: "Dokumentasi API Sistem Peminjaman Alat"
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Local Development Server"
)]

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class OpenApi {}
