<?php

namespace App\Traits;

trait FlashTrait{
    public function renderMessage(string $type, string $message) : array
    {
        return ['type' => $type,'message' => $message];
    }
}
