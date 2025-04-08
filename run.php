<?php

use App\Services\CommissionCalculator;
use App\Services\CsvReader;
use App\Services\ExchangeRate;

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$csvReader = new CsvReader('input.csv', [
	'date',
	'user_id',
	'user_type',
	'operation_type',
	'amount',
	'currency',
]);
$exchangeRate = new ExchangeRate();
$commissionCalculator = new CommissionCalculator($exchangeRate);

$history_transactions = [];
foreach ($csvReader->data() as $transaction) {
	$calculated_amount = $commissionCalculator->calculate($transaction);

	if(null == $calculated_amount) {
		echo 'null'; continue;
	}
	echo $calculated_amount. PHP_EOL . PHP_EOL;
}