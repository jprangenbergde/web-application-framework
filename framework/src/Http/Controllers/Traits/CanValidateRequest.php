<?php

declare(strict_types=1);

namespace Framework\Http\Controllers\Traits;

use Framework\Http\Requests\ValidatableRequest;
use Framework\Sessions\FlashMessengers\FlashMessage;

trait CanValidateRequest
{
    private function valid(ValidatableRequest $request): bool
    {
        $validation = $request->validate();
        if ($validation->valid()) {
            return true;
        }

        $this->flashMessenger->addMessages(
            FlashMessage::fromMessages($validation->messages())
        );

        return false;
    }
}
