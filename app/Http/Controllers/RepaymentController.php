<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Repayment;
use App\Http\Resources\RepaymentResource;
use Illuminate\Http\Request;

class RepaymentController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric',
        ]);

        // Check loan ownership using the CheckLoanOwnership middleware

        // Create the repayment
        $repayment = Repayment::create([
            'loan_id' => $validatedData['loan_id'],
            'amount' => $validatedData['amount'],
            'status' => 'PAID',
        ]);

        // Update loan status if all repayments are paid
        $loan = Loan::findOrFail($validatedData['loan_id']);
        if ($loan->repayments()->where('status', '!=', 'PAID')->doesntExist()) {
            $loan->update(['status' => 'PAID']);
        }

        return new RepaymentResource($repayment);
    }
}
