<?php

use App\Services\CsvReader;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
	/** tests */
	public function test_csv_reader_loads_data(): void
	{
		$projectRoot = dirname(__DIR__, 2);
		$filePath = $projectRoot . '/public/test.csv';
		file_put_contents($filePath, "date,user_id,user_type,operation_type,amount,currency\t\n2021-01-01,1,private,withdraw,200,EUR");

		$reader = new CsvReader('test.csv', ['date', 'user_id', 'user_type', 'operation_type', 'amount', 'currency'], true);
		$this->assertCount(1, $reader->data());
		unlink($filePath);  // cleanup
	}
}