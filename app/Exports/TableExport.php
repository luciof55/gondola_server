<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TableExport implements FromCollection
{
	protected $collection;
	
	 public function __construct($collection) {
        $this->collection = $collection;
    }
	
    public function collection()
    {
        return $this->collection;
    }
}