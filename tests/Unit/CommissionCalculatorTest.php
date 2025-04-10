<?php

use App\Services\CommissionCalculator;
use App\Services\ExchangeRate;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
	/** test
	 * @throws Exception
	 */
	public function test_calculate_commission(): void
	{
		$exchangeRate = $this->createMock(ExchangeRate::class);

		$transaction = [
			'date' => '2021-01-01',
			'user_id' => 1,
			'user_type' => 'private',
			'operation_type' => 'withdraw',
			'amount' => 200,
			'currency' => 'EUR',
		];

		$calculator = new CommissionCalculator($exchangeRate);
		$result = $calculator->calculate($transaction);
		$this->assertIsNumeric($result);
	}
}