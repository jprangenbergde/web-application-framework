<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Register;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\RegisterService;
use Framework\Http\Controllers\Attributes\Request;
use Framework\Http\Controllers\Attributes\Route;
use Framework\Http\Controllers\ControllerInterface;
use Framework\Http\Controllers\Traits\CanValidateRequest;
use Framework\Http\HttpMethod;
use Framework\Http\Middlewares\Auth\AuthenticationDisallowedMiddleware;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\RedirectResponse;
use Framework\Http\Responses\ResponseInterface;
use Framework\Sessions\FlashMessengers\FlashMessage;
use Framework\Sessions\FlashMessengers\FlashMessageType;
use Framework\Sessions\FlashMessengers\FlashMessengerInterface;
use Override;

#[Route(
    route: '/register',
    httpMethod: HttpMethod::POST,
    middlewareClasses: [
        AuthenticationDisallowedMiddleware::class,
    ]
)]
final readonly class RegisterSubmitController implements ControllerInterface
{
    use CanValidateRequest;

    public function __construct(
        private FlashMessengerInterface $flashMessenger,
        private RegisterService $registerService,
    ) {}

    #[Request(RegisterRequest::class)]
    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        /**
         * @var RegisterRequest $request
         */
        $valid = $this->valid($request);
        if (!$valid) {
            return new RedirectResponse('/register');
        }

        return $this->register($request);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->registerService->register(
            $request->firstName(),
            $request->lastName(),
            $request->email(),
            $request->password(),
        );

        $this->flashMessenger->addMessage(
            FlashMessage::fromText(
                'Registration successfully!',
                FlashMessageType::Success
            )
        );

        return new RedirectResponse('/login');
    }
}
