<?php
namespace app\Http\Controllers;

use App\Article;
use App\Http\Controllers\Controller;
use App\ParseRequest;
use App\Services\CsvService;
use App\Services\EmailService;
use App\Services\ParserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ParseController extends Controller {

    public function index()
    {
        return view('index');
    }

    public function save($id)
    {
        $this->mockAddIsSaved($id);
        return redirect("/parse-requests/{$id}");
    }

    public function csv($id)
    {
        $this->mockAddCsvAndSave($id);
        return redirect("/parse-requests/{$id}");
    }

    public function email($id)
    {
        $sendParams = $this->validate(request(), [
            'receiver' => 'required|email'
        ]);
        $emailService = new EmailService($this->mockFindRequest($id), $sendParams['receiver']);
        $emailService->send();
        return redirect("/parse-requests/{$id}?sent=true");
    }

    public function parse(Request $request)
    {
        $parseParams = $this->validate(request(), [
            'limit' => 'required|numeric'
        ]);
        $parser = new ParserService($parseParams['limit']);
        try {
            $result = $parser->parse();
            $parseRequestId = $this->mockSaveAndReturnSavedId($result);
            return redirect("/parse-requests/{$parseRequestId}");
        } catch (\Exception $e) {
            // Todo: add error handling
            var_dump($e);
            die;
        }
    }

    public function list()
    {
        return view('list', [
            'requests' => $this->mockFindRequests(),
        ]);
    }

    public function view($id)
    {
        return view('view', [
            'request' => $this->mockFindRequest($id),
        ]);
    }

    private function mockFindRequests() {
        // Todo: delete this function and search parse requests in db
        $test = new ParseRequest();
        $test->setCreatedAt(new Carbon);

        return [
            $test
        ];
    }

    private function mockFindRequest($id): ParseRequest {
        // Todo: delete this function and search parse request in db
        $test = new ParseRequest();
        $test->id = 1;
        $test->is_saved = false;
        // Uncomment to explore page state when request is saved or csv file is generated
//        $test->is_saved = true;
//        $test->csv_file_link = '/files/test.csv';
        $test->setCreatedAt(new Carbon);
        $article = new Article;
        $article->title = 'lol';
        $article->content = 'lol';
        $article->is_bold = false;
        $article->publish_date = new Carbon;
        $article->link = 'http://vk.com/';
        $test->articles = [
            $article
        ];

        return $test;
    }

    private function mockSaveAndReturnSavedId($parseResult): int {
        // Todo: delete this function and save parse result to db
        // var_dump($parseResult); die; Uncomment to explore $parseResult structure
        return 1;
    }

    private function mockAddIsSaved($id) {
        // Todo: delete this function and save result to db
        $request = $this->mockFindRequest($id);
        $request->is_saved = true;
        // $request->save();
    }

    private function mockAddCsvAndSave($id) {
        // Todo: delete this function and save result to db
        $request = $this->mockFindRequest($id);
        $csvService = new CsvService($request);
        $request->csv_file_link = $csvService->save();
        // $request->save();
    }
}