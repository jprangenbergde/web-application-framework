<?php

declare(strict_types=1);

namespace Framework\Events\EventManagers;

use Framework\Events\ObservableInterface;
use Framework\FactoryManagers\Attributes\InstanceLocked;
use Override;

#[InstanceLocked]
final class EventManager implements EventManagerInterface
{
    /**
     * @var array<array<class-string<ObservableInterface>>>
     */
    private array $observables = [];

    private array $registeredObservers = [];

    #[Override]
    public function register(array $observers): void
    {
        foreach ($observers as $observer) {
            if (array_key_exists($observer::class, $this->registeredObservers)) {
                continue;
            }

            $observer->observe($this);
            $this->registeredObservers[$observer::class] = true;
        }
    }

    #[Override]
    public function attach(string $observableClass, callable $observer, int $priority = 0): void
    {
        $this->observables[$observableClass][] = [
            'priority' => $priority,
            'observer' => $observer,
        ];
    }

    #[Override]
    public function detach(string $observableClass): void
    {
        unset($this->observables[$observableClass]);
    }

    #[Override]
    public function notify(ObservableInterface $observable): void
    {
        $observables = array_merge(
            array_merge(
                $this->observables['*'] ?? [],
                ...
                array_values(
                    array_map(
                        fn($implements) => $this->observables[$implements] ?? [],
                        class_implements($observable)
                    )
                ),
            ),
            $this->observables[$observable::class] ?? []
        );

        usort($observables, fn($a, $b) => $b['priority'] <=> $a['priority']);

        foreach (array_column($observables ?? [], 'observer') as $observer) {
            $observer($observable);
        }
    }
}
