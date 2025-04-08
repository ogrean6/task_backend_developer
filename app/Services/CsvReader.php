<?php

namespace App\Services;

class CsvReader
{
	/** @var string  */
	private string $filePath;
	private array $columns;

	/**
	 * @param string $filePath
	 * @param array $columns
	 */
	public function __construct(string $filePath, array $columns)
	{
		$this->filePath = __DIR__ . '/../../public/' .$filePath;
		$this->columns = $columns;
	}

	/**
	 * @return array
	 */
	public function data(): array
	{
		$data = [];
		if (($handle = fopen($this->filePath, "r")) !== false) {
			while (($row = fgetcsv($handle)) !== false) {
				$rows = [];
				foreach ($this->columns as $key => $column) {
					$rows[$column] = $row[$key] ?? null;
				}
				$data[] = $rows;
			}
			fclose($handle);
		}

		return $data;
	}
}