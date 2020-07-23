<?php

namespace OZiTAG\Tager\Backend\Core\Support;

use Illuminate\Support\MessageBag as BaseMessageBag;

class MessageBag extends BaseMessageBag
{

    /**
     * Add a message to the message bag.
     *
     * @param  string  $key
     * @param  string  $message
     * @return \Illuminate\Support\MessageBag
     */
    public function set($key, $message)
    {
        if ($this->isUnique($key, $message)) {
            $this->messages[$key] = $message;
        }

        return $this;
    }
}
