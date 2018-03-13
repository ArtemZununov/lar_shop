<?php
namespace App\Services;

use App\ParseRequest;

class CsvService {

    private $request;

    function __construct(ParseRequest $request)
    {
        $this->request = $request;
    }

    public function save(): string {
        // TODO: add csv build and save methods
        return '/files/test.csv';
    }
}