<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepaymentTest extends TestCase
{
    use RefreshDatabase;

    public function testRepaymentBelongsToLoan()
    {
        $loan = Loan::factory()->create();
        $repayment = Repayment::factory()->create(['loan_id' => $loan->id]);

        $this->assertInstanceOf(Loan::class, $repayment->loan);
        $this->assertEquals($loan->id, $repayment->loan->id);
    }
}
