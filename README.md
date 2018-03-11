# Readme

## Installation
    Run `composer install`.
    Copy .env.example to .env and specify your local variables
    Run `php artisan migrate` to deploy db schema
    Run `php artisan serve` to start develop server
    
## Develop
    Run `npm install`
    Run `npm run watch` to enable live assets compiling
    
## Parser
    Parsing is written like [phantomjs](http://phantomjs.org/) script.
    You can run it manually from terminal.

**On Windows:**

`./parser/bin/phantomjs.exe news.parser.js  https://www.pravda.com.ua/rus/news/ 0 true`

**On Mac:** 

`./parser/bin/phantomjs.exe news.parser.js  https://www.pravda.com.ua/rus/news/ 0 true`