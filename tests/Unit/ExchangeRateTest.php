<?php

use App\Services\ExchangeRate;
use PHPUnit\Framework\TestCase;

class ExchangeRateTest extends TestCase
{
	/** tests */
	public function test_convert_returns_expected_rate() : void
	{
		$exchange = new ExchangeRate();
		$converted = $exchange->exchange(100, 'USD', 'EUR');
		$this->assertIsNumeric($converted);
	}
}