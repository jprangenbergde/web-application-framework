<?php

declare(strict_types=1);

namespace Framework\Consoles;

use Framework\Consoles\Commands\Attributes\Command;
use Framework\Consoles\Commands\CommandInterface;
use Framework\Consoles\Outputs\ConsoleOutputInterface;
use Framework\Events\LifecycleEvents\CommandFinished;
use Framework\Events\LifecycleEvents\CommandStarted;
use Framework\FactoryManagers\Attributes\InstanceLocked;
use Framework\FactoryManagers\FactoryProducerInterface;
use ReflectionClass;
use Override;

#[InstanceLocked]
final class Console implements ConsoleInterface
{
    private array $commandClasses = [];

    public function __construct(
        private readonly FactoryProducerInterface $factoryProducer,
        private readonly ConsoleOutputInterface $consoleOutput
    ) {}

    #[Override]
    public function register(array $commandClasses): void
    {
        $this->commandClasses = array_merge($commandClasses, $this->commandClasses);
    }

    #[Override]
    public function run(array $arguments): void
    {
        $resolvedCommands = $this->resolveCommands();

        if (count($arguments) === 1) {
            $this->listCommands($resolvedCommands);
        } else {
            $this->executeCommand($resolvedCommands, $arguments);
        }
    }

    private function resolveCommandClass(string $commandClass): ?array
    {
        $reflectionClass = new ReflectionClass($commandClass);
        $commandAttributes = $reflectionClass->getAttributes(Command::class);

        if ($commandAttributes === []) {
            return null;
        }

        /** @var Command $commandAttribute */
        $commandAttribute = $commandAttributes[0]->newInstance();

        return [$commandAttribute->commandName, $commandClass];
    }

    private function resolveCommands(): array
    {
        $resolvedCommands = [];

        foreach ($this->commandClasses as $commandClass) {
            $resolvedCommand = $this->resolveCommandClass($commandClass);
            if (!$resolvedCommand) {
                continue;
            }

            [$command, $commandClass] = $resolvedCommand;
            $resolvedCommands[$command] = $commandClass;
        }

        return $resolvedCommands;
    }

    private function listCommands(array $resolvedCommands): void
    {
        $this->consoleOutput->info('Usage:');
        $this->consoleOutput->line('    command [options] [arguments]');
        $this->consoleOutput->newLine();

        foreach ($resolvedCommands as $command => $commandClass) {
            $this->consoleOutput->success(sprintf('%s [%s]', $command, $commandClass));
        }
    }

    private function executeCommand(array $resolvedCommands, array $arguments): void
    {
        $commandName = $arguments[1];

        $commandClass = $resolvedCommands[$commandName] ?? null;
        if (!is_subclass_of($commandClass, CommandInterface::class)) {
            $this->consoleOutput->error('Could not find any commands for given command');

            return;
        }

        /** @var CommandInterface $command */
        $command = $this->factoryProducer->get($commandClass);

        CommandStarted::occur($command, $this->consoleOutput);
        $command->run($arguments, $this->consoleOutput);
        CommandFinished::occur($command, $this->consoleOutput);
    }
}
