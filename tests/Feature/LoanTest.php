<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCustomerCanCreateLoan()
    {
        $user = User::factory()->create();

        $loanData = [
            'amount' => 10000,
            'term' => 3,
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/loans', $loanData);

        $response->assertCreated();
        $response->assertJsonStructure([
            'id',
            'amount',
            'term',
            'status',
            'created_at',
            'updated_at',
        ]);
    }

    public function testAdminCanApproveLoan()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $loan = Loan::factory()->create(['status' => 'PENDING']);

        $response = $this->actingAs($admin)
            ->putJson('/api/loans/' . $loan->id, ['status' => 'APPROVED']);

        $response->assertOk();
        $response->assertJson(['status' => 'APPROVED']);
    }

    public function testCustomerCanViewTheirLoan()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->getJson('/api/loans/' . $loan->id);

        $response->assertOk();
        $response->assertJson(['id' => $loan->id]);
    }
}
