<?php

namespace App\Http\Controllers;

use App\Models\PremiumPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminPremiumPaymentProofController extends Controller
{
    public function preview(Request $request, PremiumPayment $premiumPayment): BinaryFileResponse
    {
        $this->authorizeAccess($request);

        abort_unless(filled($premiumPayment->payment_proof), 404);
        abort_unless(Storage::disk('public')->exists($premiumPayment->payment_proof), 404);

        $path = Storage::disk('public')->path($premiumPayment->payment_proof);
        $mimeType = Storage::disk('public')->mimeType($premiumPayment->payment_proof) ?: 'application/octet-stream';

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$premiumPayment->paymentProofDownloadName().'"',
        ]);
    }

    public function download(Request $request, PremiumPayment $premiumPayment): BinaryFileResponse
    {
        $this->authorizeAccess($request);

        abort_unless(filled($premiumPayment->payment_proof), 404);
        abort_unless(Storage::disk('public')->exists($premiumPayment->payment_proof), 404);

        return Storage::disk('public')->download(
            $premiumPayment->payment_proof,
            $premiumPayment->paymentProofDownloadName(),
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
