<?php
namespace App\Services;

use App\ParseRequest;

class EmailService {

    private $request;

    private $receiver;

    function __construct(ParseRequest $request, string $receiver)
    {
        $this->request = $request;
        $this->receiver = $receiver;
    }

    public function send(): bool {
        // TODO: add email send code from laravel https://laravel.com/docs/5.6/mail
        // mail($this->receiver);
        return true;
    }
}