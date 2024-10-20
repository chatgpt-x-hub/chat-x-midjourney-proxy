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

Route::group('/api', [
    function($request, $next) {
        try {
            return \React\Async\async(fn() => $next($request))();
        } catch (Throwable $e) {
            return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code' => 500,
                'msg' => $e->getMessage(),
                'taskId' => null,
                'data' => [],
                'ban-words' => $e->banWord ?? ''
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
],function () {
    Route::post('/imagine', 'App\Midjourney\Controller\Image@imagine');
    Route::post('/action', 'App\Midjourney\Controller\Image@action');
    Route::post('/blend', 'App\Midjourney\Controller\Image@blend');
    Route::post('/describe', 'App\Midjourney\Controller\Image@describe');
    Route::get('/task', 'App\Midjourney\Controller\Task@fetch');
});