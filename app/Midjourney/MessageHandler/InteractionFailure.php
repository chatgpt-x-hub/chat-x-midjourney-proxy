<?php

/**
 * This file is part of webman/midjourney-proxy.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Midjourney\MessageHandler;

use App\Midjourney\Discord;
use App\Midjourney\Task;
use App\Midjourney\TaskCondition;

class InteractionFailure extends Base
{
    public static function handle($message): bool
    {
        $nonce = $message['d']['nonce'] ?? '';
        $messageType = $message['t'] ?? '';
        if ($messageType === Discord::INTERACTION_FAILURE && $nonce) {
            $task = Discord::getRunningTaskByCondition((new TaskCondition())->nonce($nonce));
            $params = $task->params();
            $params[Discord::INTERACTION_FAILURE] = true;
            $task->params($params);
            $task->save();
        }
        return false;
    }
}