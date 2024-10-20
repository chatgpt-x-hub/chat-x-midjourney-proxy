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
use Illuminate\Support\Facades\Log;
use App\Midjourney\Task;
use App\Midjourney\TaskCondition;

class VaryRegionProgress extends Base
{
    public static function handle($message): bool
    {
        $messageType = $message['t'] ?? '';
        $interactionId = $message['d']['interaction_metadata']['id'] ?? '';
        $messageId = $message['d']['id'] ?? '';
        if ($messageType === Discord::MESSAGE_CREATE && $interactionId) {
            if (!$task = Discord::getRunningTaskByCondition((new TaskCondition())->action(Task::ACTION_VARIATION_REGION)->params([
                'interactionId' => $interactionId
            ]))) {
                Log::debug("VaryRegionProgress no task found interactionId=$interactionId messageId=$messageId");
                return false;
            }
            $task->messageId($messageId);
            if ($messageHash = Discord::getMessageHash($message)) {
                $task->messageHash($messageHash);
            }
            $task->buttons(Discord::getButtons($message));
            $task->save();
            return true;
        }
        return false;
    }
}