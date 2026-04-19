<?php

namespace App\Http\Controllers;

use App\Models\MaterialPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminMaterialPaymentProofController extends Controller
{
    public function preview(Request $request, MaterialPayment $materialPayment): BinaryFileResponse
    {
        $this->authorizeAccess($request);

        abort_unless(filled($materialPayment->payment_proof), 404);
        abort_unless(Storage::disk('public')->exists($materialPayment->payment_proof), 404);

        $path = Storage::disk('public')->path($materialPayment->payment_proof);
        $mimeType = Storage::disk('public')->mimeType($materialPayment->payment_proof) ?: 'application/octet-stream';

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$materialPayment->paymentProofDownloadName().'"',
        ]);
    }

    public function download(Request $request, MaterialPayment $materialPayment): BinaryFileResponse
    {
        $this->authorizeAccess($request);

        abort_unless(filled($materialPayment->payment_proof), 404);
        abort_unless(Storage::disk('public')->exists($materialPayment->payment_proof), 404);

        return Storage::disk('public')->download(
            $materialPayment->payment_proof,
            $materialPayment->paymentProofDownloadName(),
        );
    }

    protected function authorizeAccess(Request $request): void
    {
        abort_unless(
            $request->user()?->hasAnyRole(['super_admin', 'admin']),
            403,
        );
    }
}
