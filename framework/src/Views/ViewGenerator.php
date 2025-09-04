<?php

declare(strict_types=1);

namespace Framework\Views;

use Framework\Factories\Attributes\Config;
use Framework\FactoryManagers\Attributes\InstanceLocked;
use Framework\Views\ViewHelpers\ViewHelperInterface;
use Override;

#[InstanceLocked]
final class ViewGenerator implements ViewGeneratorInterface, ViewHelperRegistrarInterface
{
    /**
     * @var array<ViewHelperInterface>
     */
    private array $viewHelpers = [];

    public function __construct(
        #[Config('view.path')]
        private readonly string $viewPath,
    ) {}

    #[Override]
    public function register(array $viewHelpers): void
    {
        $this->viewHelpers = array_merge_recursive($this->viewHelpers, $viewHelpers);
    }

    #[Override]
    public function generate(string $view, array $data = []): string
    {
        $generator = static function (string $view, array $data, string $viewPath, array $viewHelpers): string {
            /** @var array<ViewHelperInterface> $viewHelpers */
            foreach ($viewHelpers as $viewHelper) {
                $data[$viewHelper->getName()] = $viewHelper;
            }

            extract($data);
            unset($data, $viewHelper, $viewHelpers);

            ob_start();
            require $viewPath . '/' . $view . '.php';

            return ob_get_clean();
        };

        return $generator(
            $view,
            $data,
            $this->viewPath,
            $this->viewHelpers
        );
    }
}
