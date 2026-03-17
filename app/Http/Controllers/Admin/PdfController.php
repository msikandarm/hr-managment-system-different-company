<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    public function pdf_generate($pdf)
    {
        // TODO: Implement PDF generation
        return response()->json(['message' => 'PDF generation not implemented']);
    }
}
