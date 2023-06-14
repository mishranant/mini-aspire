<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\Repayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCustomerCanAddRepayment()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $repaymentData = [
            'loan_id' => $loan->id,
            'amount' => 3333.33,
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/repayments', $repaymentData);

        $response->assertCreated();
        $response->assertJsonStructure([
            'id',
            'loan_id',
            'amount',
            'status',
            'created_at',
            'updated_at',
        ]);
    }

    public function testRepaymentUpdatesLoanStatusToPaid()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);
        $repayment = Repayment::factory()->create(['loan_id' => $loan->id]);

        $response = $this->actingAs($user)
            ->postJson('/api/repayments', [
                'loan_id' => $loan->id,
                'amount' => $repayment->amount,
            ]);

        $response->assertCreated();
        $response->assertJson(['status' => 'PAID']);

        $loan->refresh();
        $this->assertEquals('PAID', $loan->status);
    }
}
