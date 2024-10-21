# chatgpt-x-hub/chat-x-midjourney-proxy

## Introduction

https://github.com/webman-php/midjourney-proxy 的 reactphp 实现


## install

```bash
composer create-project chatgpt-x-hub/chat-x-midjourney-proxy -vvv dev-master
```


## config

cp .env.example .env

```
MIDJOURNEY_HTTP_PROXY=
DISCORD_BOT_TOKEN=
DISCORD_GUILD_ID=
DISCORD_CHANNEL_ID=

MI_NOTIFY_URL=
MJ_API_SECRET=
```

## run

```
php artisan reactphp:http start
```

## api

```
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
```



## License

MIT
