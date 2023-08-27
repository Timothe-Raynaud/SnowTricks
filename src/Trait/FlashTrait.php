<?php

namespace App\Trait;

trait FlashTrait{
    public function renderMessage(string $type, string $message) : array
    {
        return ['type' => $type,'message' => $message];
    }
}