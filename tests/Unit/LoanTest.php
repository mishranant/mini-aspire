<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function testLoanHasRepayments()
    {
        $loan = Loan::factory()->create();
        $repayment = Repayment::factory()->create(['loan_id' => $loan->id]);

        $this->assertTrue($loan->repayments->contains($repayment));
    }

    public function testLoanIsPaidWhenAllRepaymentsArePaid()
    {
        $loan = Loan::factory()->create();
        $repayment1 = Repayment::factory()->create(['loan_id' => $loan->id, 'status' => 'PAID']);
        $repayment2 = Repayment::factory()->create(['loan_id' => $loan->id, 'status' => 'PAID']);
        $repayment3 = Repayment::factory()->create(['loan_id' => $loan->id, 'status' => 'PAID']);

        $this->assertTrue($loan->isPaid());
    }

    public function testLoanIsNotPaidWhenAnyRepaymentIsPending()
    {
        $loan = Loan::factory()->create();
        $repayment1 = Repayment::factory()->create(['loan_id' => $loan->id, 'status' => 'PAID']);
        $repayment2 = Repayment::factory()->create(['loan_id' => $loan->id, 'status' => 'PENDING']);
        $repayment3 = Repayment::factory()->create(['loan_id' => $loan->id, 'status' => 'PAID']);

        $this->assertFalse($loan->isPaid());
    }
}
