<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Http\Resources\LoanResource;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'term' => 'required|integer',
        ]);

        // Create the loan
        $loan = Loan::create([
            'user_id' => auth()->user()->id,
            'amount' => $validatedData['amount'],
            'term' => $validatedData['term'],
            'status' => 'PENDING',
        ]);

        // Generate scheduled repayments
        // Implement the logic to calculate repayment dates and amounts based on loan details

        return new LoanResource($loan);
    }

    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        // Check loan ownership using the CheckLoanOwnership middleware

        return new LoanResource($loan);
    }
}
