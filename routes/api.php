<?php

use ReactphpX\LaravelReactphp\Facades\Route;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Midjourney\Discord;
use App\Midjourney\Task;

use React\EventLoop\Loop;


// Loop::addTimer(10, \React\Async\async(function () {
//     echo "Hello world\n";
//     app('reactphp.filesystem')->file('/root/Code/chat-x-midjourney/storage/app/data/midjourney/lists/discord-pending-tasks')->putContents('22222')->then(function () {
//         echo "File cleared\n";
//     });
// }));

Task::init(Config::get('midjourney.store'));

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
    public function index(ServerRequestInterface $request)
    {
        return Response::plaintext(
            "Hello wörld!\n"
        );
    }
};

Route::get('/at', get_class($class) . '@index');

Route::group(
    config('midjourney.settings.apiPrefix', ''),
    function ($request, $next) {
        try {
            if (config('midjourney.settings.secret')) {
                $secrets = explode(',', config('midjourney.settings.secret'));
                if (!in_array($request->getHeaderLine('mj-api-secret'), $secrets)) {
                    return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                        'code' => 403,
                        'msg' => '403 Api Secret 错误',
                        'taskId' => null,
                        'data' => []
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
            }
            return \React\Async\async(fn() => $next($request))()->then(null, function ($e) {
                return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                    'code' => 500,
                    'msg' => $e->getMessage(),
                    'taskId' => null,
                    'data' => [],
                    'ban-words' => $e->banWord ?? ''
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            });
        } catch (Throwable $e) {
            return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code' => 500,
                'msg' => $e->getMessage(),
                'taskId' => null,
                'data' => [],
                'ban-words' => $e->banWord ?? ''
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    },
    function () {
        // {
        //     "prompt": "a cat",
        //     "images": [url1, url2, ...], // 可选参数
        //     "notifyUrl": "https://your-server.com/notify", // 可选参数
        // }
        // {
        //     "code": 0,
        //     "msg": "ok",
        //     "taskId": "1710816049856103374",
        //     "data": []
        // }
        Route::post('/imagine', 'App\Midjourney\Controller\Image@imagine');
        // {
        //     "taskId": "1710816049856103374",
        //     "customId": "MJ::JOB::upsample::1::749b4d14-75ec-4f16-8765-b2b9a78125fb",
        //     "notifyUrl": "https://your-server.com/notify", // 可选参数
        // }
        // {
        //     "code": 0,
        //     "msg": "ok",
        //     "taskId": "1710816302060986090",
        //     "data": []
        // }
        Route::post('/action', 'App\Midjourney\Controller\Image@action');
        // {
        //     "images": [url],
        //     "notifyUrl": "https://your-server.com/notify", // 可选参数
        // }
        // {
        //     "code": 0,
        //     "msg": "ok",
        //     "taskId": "1710816302060386071",
        //     "data": []
        // }
        Route::post('/describe', 'App\Midjourney\Controller\Image@describe');
        // {
        //     "images": [url1, url2],
        //     "notifyUrl": "https://your-server.com/notify", // 可选参数
        // }
        // {
        //     "code": 0,
        //     "msg": "ok",
        //     "taskId": "1710816302060354172",
        //     "data": []
        // }
        Route::post('/blend', 'App\Midjourney\Controller\Image@blend');
        // /task/fetch?taskId=1710816049856103374 
        /*
        {
            "code": 0,
            "msg": "success",
            "data": {
              "id": "1710816049856103374",
              "action": "IMAGINE",
              "status": "FINISHED",
              "submitTime": 1710903739,
              "startTime": 1710903739,
              "finishTime": 1710903844,
              "progress": "100%",
              "imageUrl": "https:\/\/your_cdn.com\/attachments\/1148151204884726471\/121984387748450658284\/a_cat._65e72369d-1db1-5be4-9566-71056a5b0caf.png?ex=660cc723&is=65fa5223&hm=0d9b721610b62101c7cb4c0f3bf4e364cdd69be3441b9c3b1c200d20b309d97e&",
              "imageRawUrl": "https:\/\/cdn.discordapp.com\/attachments\/1148151204884726471\/121984387748450658284\/a_cat._65e72369d-1db1-5be4-9566-71056a5b0caf.png?ex=660cc723&is=65fa5223&hm=0d9b721610b62101c7cb4c0f3bf4e364cdd69be3441b9c3b1c200d20b309d97e&",
              "prompt": "A cat. --v 6.0 --relax",
              "finalPrompt": "A cat. --v 6.0 --relax",
              "params": [],
              "images": [],
              "description": null,
              "failReason": null,
              "discordId": "1148151204875075657",
              "data": [],
              "buttons": [
                [
                  {
                    "type": 2,
                    "style": 2,
                    "label": "U1",
                    "custom_id": "MJ::JOB::upsample::1::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  },
                  {
                    "type": 2,
                    "style": 2,
                    "label": "U2",
                    "custom_id": "MJ::JOB::upsample::2::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  },
                  {
                    "type": 2,
                    "style": 2,
                    "label": "U3",
                    "custom_id": "MJ::JOB::upsample::3::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  },
                  {
                    "type": 2,
                    "style": 2,
                    "label": "U4",
                    "custom_id": "MJ::JOB::upsample::4::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  },
                  {
                    "type": 2,
                    "style": 2,
                    "emoji": {
                      "name": "🔄"
                    },
                    "custom_id": "MJ::JOB::reroll::0::65e72369d-1db1-5be4-9566-71056a5b0caf::SOLO"
                  }
                ],
                [
                  {
                    "type": 2,
                    "style": 2,
                    "label": "V1",
                    "custom_id": "MJ::JOB::variation::1::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  },
                  {
                    "type": 2,
                    "style": 2,
                    "label": "V2",
                    "custom_id": "MJ::JOB::variation::2::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  },
                  {
                    "type": 2,
                    "style": 2,
                    "label": "V3",
                    "custom_id": "MJ::JOB::variation::3::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  },
                  {
                    "type": 2,
                    "style": 2,
                    "label": "V4",
                    "custom_id": "MJ::JOB::variation::4::65e72369d-1db1-5be4-9566-71056a5b0caf"
                  }
                ]
              ]
            }
        }
        */
        Route::get('/task', 'App\Midjourney\Controller\Task@fetch');
    }
);
