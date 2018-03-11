<?php
namespace App\Services;

class ParserService {
    const PHANTOM_PATH = 'parser/bin/phantomjs';
    const SCRIPT_PATH = 'parser/news.parser.js';
    const PARSE_URL = 'https://www.pravda.com.ua/rus/news/';

    private $limit;

    function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    public function parse() {
        $base = __DIR__ . '/../../';
        $cmd = sprintf(
            "%s %s '%s' %s %s" ,
            $base.self::PHANTOM_PATH,
            $base.self::SCRIPT_PATH,
            self::PARSE_URL,
            $this->limit,
            'false'
        );
        ob_start();
        $rawResult = system($cmd, $returnVal);
        ob_clean();
        $Result = json_decode($rawResult);
        if ($returnVal || !$Result) {
            throw new \Exception('Parsing error');
        }
        return $Result;
    }
}