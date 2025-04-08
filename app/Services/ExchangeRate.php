<?php

namespace App\Services;

class ExchangeRate
{
	const URL = 'https://api.exchangerate.host/live';

	/** @var array|float[]  */
	private array $rates;

	public function __construct()
	{
		$this->rates = $this->externalRates(['source' => $_ENV['CURRENCY']]);
	}

	/**
	 * @param array $params
	 * @return array
	 */
	public function externalRates(array $params = []): array
	{
		$rates = [
			'EURUSD' => 1.1497,
			'EURJPY' => 129.53
		];

		$params = array_merge($params, ['access_key' => $_ENV['EXCHANGE_RATE_ACCESS_KEY']]);
		$url = self::URL . '?' . http_build_query($params);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($response, true);
		if (isset($data['success']) && $data['success']) {
			$rates = $data['quotes'];
		}

		return $rates;
	}

	/**
	 * @param string $currencyFrom
	 * @param string $currencyTo
	 * @return float
	 */
	public function rate(string $currencyFrom, string $currencyTo): float
	{
		if ($currencyFrom == $currencyTo) return 1;

		return $this->rates[$currencyTo . $currencyFrom] ?? 1;
	}

	/**
	 * @param float $amount
	 * @param string $currencyFrom
	 * @param string $currencyTo
	 * @return float
	 */
	public function exchange(float $amount, string $currencyFrom, string $currencyTo): float
	{
		return $amount / $this->rate($currencyFrom, $currencyTo);
	}
}