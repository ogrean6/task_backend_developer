<?php

namespace App\Services;

class CsvReader
{
	/** @var string  */
	private string $filePath;
	private array $columns;
	private bool $withHeaders;

	/**
	 * @param string $filePath
	 * @param array $columns
	 * @param bool $withHeaders
	 */
	public function __construct(string $filePath, array $columns, bool $withHeaders)
	{
		$this->filePath = __DIR__ . '/../../public/' .$filePath;
		$this->columns = $columns;
		$this->withHeaders = $withHeaders;
	}

	/**
	 * @return array
	 */
	public function data(): array
	{
		$data = [];
		echo $this->filePath;
		if (($handle = fopen($this->filePath, "r")) !== false) {
			$counter = 0;
			while (($row = fgetcsv($handle)) !== false) {
				if($this->withHeaders && $counter === 0) {
					$counter++;
					continue;
				}
				$rows = [];
				foreach ($this->columns as $key => $column) {
					$rows[$column] = $row[$key] ?? null;
				}
				$data[] = $rows;
				$counter++;
			}
			fclose($handle);
		}

		return $data;
	}
}