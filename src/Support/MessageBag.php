<?php

namespace OZiTAG\Tager\Backend\Core\Support;

use Illuminate\Support\MessageBag as BaseMessageBag;

class MessageBag extends BaseMessageBag
{
    public function set(string $key, string $message): BaseMessageBag
    {
        if ($this->isUnique($key, $message)) {
            $this->messages[$key] = $message;
        }

        return $this;
    }
}
