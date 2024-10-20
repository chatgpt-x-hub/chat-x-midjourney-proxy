<?php

use ReactphpX\LaravelReactphp\Facades\Route;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Midjourney\Discord;
use App\Midjourney\Task;

Task::init($config['midjourney.store']);

foreach (Config::get('midjourney.accounts') as $account) {
    if (isset($account['enable']) && !$account['enable']) {
        continue;
    }
    foreach ($account as $key => $value) {
        if (empty($value)) {
            Log::error("Discord account config error $key is empty");
            continue 2;
        }
    }
    new Discord($account);
}

Route::get('/', function (ServerRequestInterface $request) {
    return Response::plaintext(
        "Hello wörld!\n"
    );
});

$class = new class {
    public function index(ServerRequestInterface $request) {
        return Response::plaintext(
            "Hello wörld!\n"
        );
    }
};

Route::get('/at', get_class($class).'@index');