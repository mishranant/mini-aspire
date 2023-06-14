<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Loan;

class CheckLoanOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $loanId = $request->route('id');
        $userId = $request->user()->id;
        
        // Check if the loan belongs to the authenticated user
        $loan = Loan::where('id', $loanId)
            ->where('user_id', $userId)
            ->first();
        
        if (!$loan) {
            return response()->json(['error' => 'Loan not found or does not belong to the user'], 404);
        }
        
        return $next($request);
    }
}
