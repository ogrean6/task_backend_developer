<?php

namespace App\Services;

class CommissionCalculator
{
	private ExchangeRate $exchangeRateService;
	public array $historyPrivateWithdrawals = [];
	private const PRIVATE_FREE_LIMIT = 1000.00;
	private const PRIVATE_FREE_COUNT = 3;

	const OPERATION_TYPE_DEPOSIT = 'deposit';
	const OPERATION_TYPE_WITHDRAW = 'withdraw';
	const USER_TYPE_BUSINESS = 'business';

	public function __construct(ExchangeRate $exchangeRateService)
	{
		$this->exchangeRateService = $exchangeRateService;
	}

	/**
	 * @param array $transaction
	 * @return string
	 */
	public function calculate(array $transaction): string
	{
		$commission = $this->commission($transaction);

		return number_format(ceil($commission * 10) / 10, 2, '.', '');
	}

	/**
	 * @param array $transaction
	 * @return float|null
	 */
	public function commission(array $transaction): ?float
	{
		$amount = $transaction['amount'];
		$currency_from = $transaction['currency'];
		$currency_to = $_ENV["CURRENCY"];
		$amount = $this->exchangeRateService->exchange($amount, $currency_from, $currency_to);

		//deposit
		if ($transaction['operation_type'] === self::OPERATION_TYPE_DEPOSIT) return $amount * 0.0003;
		//business clients withdraw
		if ($transaction['user_type'] === self::USER_TYPE_BUSINESS) return $amount * 0.005;

		//private clients withdraw
		$weekKey = date('oW', strtotime($transaction['date'])) . '-' . $transaction['user_id'];
		$remainingFree = self::PRIVATE_FREE_LIMIT - array_sum($this->historyPrivateWithdrawals[$weekKey] ?? []);

		$this->historyPrivateWithdrawals[$weekKey][] = $amount;
		if (count($this->historyPrivateWithdrawals[$weekKey]) > self::PRIVATE_FREE_COUNT) return $amount * 0.003;
		if ($remainingFree < 0) return $amount * 0.003;
		if($amount <= $remainingFree) return 0;

		return ($amount - $remainingFree) * 0.003;
	}
}