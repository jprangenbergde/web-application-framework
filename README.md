# Web Application Framework

A **100% self-written PHP framework** demonstrating advanced software architecture principles, design patterns, and modern PHP development practices. This project showcases a complete web application framework built from the ground up, featuring a robust dependency injection system, event-driven architecture, middleware support, and comprehensive tooling.

## 🎓 Educational Purpose & Talent Development

This project was initiated as a **comprehensive training and supervision tool** for apprentices and junior developers, designed to ensure strong talent development through hands-on exposure to enterprise-level software architecture.

### **Learning Objectives**
- **Design Pattern Mastery**: Comprehensive implementation of 13 major design patterns
- **Modern PHP Practices**: Strict typing, attributes, readonly classes, and advanced language features
- **Software Architecture**: Layered architecture with clear separation of concerns
- **Code Quality Standards**: Static analysis, type checking, and architecture enforcement
- **Best Practices**: Security, performance optimization, and maintainability

### **Supervision Features**
- **Comprehensive Documentation**: Detailed README serves as learning material
- **Clear Architecture**: Well-structured codebase for easy navigation and understanding
- **Extensible Design**: Framework encourages experimentation and feature additions
- **Quality Assurance**: Built-in tools for teaching code quality standards

## 🏗️ Architecture Overview

This framework implements a **layered architecture** with clear separation of concerns, following SOLID principles and modern design patterns. The architecture is built around several core components:

### Core Components

- **Application Layer**: Central orchestrator managing the request lifecycle
- **Factory Management**: Advanced dependency injection with attribute-based configuration
- **HTTP Layer**: Request/Response handling with middleware support
- **Event System**: Observable pattern implementation with lifecycle events
- **Routing**: Attribute-based routing with constraint support
- **View System**: Template engine with view helpers
- **Validation**: Chain-based validation system
- **Queue System**: Database-backed job queuing
- **Console**: Command-line interface with attribute-based commands

## 🚀 Key Features

### 1. Advanced Dependency Injection System

The framework features a sophisticated dependency injection container with:

- **Attribute-based configuration** using PHP 8 attributes
- **Autowiring** with dependency resolution
- **Factory registration** with custom factory classes
- **Instance locking** to prevent flushing of critical instances across requests
- **Memory management** with automatic flushing
- **Flyweight pattern** for instance caching and memory optimization

```php
#[InstanceLocked]
#[ProducedBy(RouterFactory::class)]
final class Router implements RouterInterface
{
    // Implementation with automatic dependency injection
    // InstanceLocked prevents this instance from being flushed between requests
}

// Flyweight pattern: Global instance access with caching
$router = instance(RouterInterface::class); // Returns cached instance
```

### 2. Advanced Event-Driven Architecture

Comprehensive event system implementing the Observer pattern with multiple event types and priority-based handling:

#### **Event System Components**

**Base Event Class**
```php
abstract readonly class Event implements ObservableInterface
{
    use CanOccur; // Provides static occur() method
}
```

**Event Occurrence**
```php
// Static method for firing events
ApplicationBootStarted::occur();
RequestCreated::occur($request);
ResponseSent::occur($response);
```

#### **Lifecycle Events**
Automatic events fired during application lifecycle:

- **ApplicationBootStarted**: Fired when application boot begins
- **ApplicationBooted**: Fired when application boot completes
- **RequestCreated**: Fired when a new request is created
- **RouterCreated**: Fired when router is instantiated
- **RouterMatched**: Fired when route matching completes
- **ResponseCreated**: Fired when response is created
- **ResponseSent**: Fired when response is sent
- **RunFinished**: Fired when request handling completes
- **WorkerHandlerStarted/Finished**: Fired for worker lifecycle

#### **Custom Application Events**
Domain-specific events for business logic:

```php
final readonly class Registered extends Event
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
    ) {}
}
```

#### **Queueable Events**
Events can be queued for asynchronous processing using the `CanQueueObservables` trait:

```php
// Event that can be queued and processed asynchronously
final readonly class EmailNotificationEvent extends Event implements QueueableInterface
{
    use CanQueueObservables;

    public function __construct(
        public string $recipient,
        public string $subject,
        public string $content
    ) {}

    public function getQueuePriority(): QueuePriority
    {
        return QueuePriority::High; // High priority = processed first
    }

    public function execute(): void
    {
        // This method is called when the event is processed from the queue
        // It automatically fires the event using self::occur($this)
    }
}

// Queue the event for asynchronous processing
EmailNotificationEvent::queue($recipient, $subject, $content);
```

#### **Observer Pattern Implementation**
Observers register event handlers with priority support:

```php
final class RegisteredEmailListener implements ObserverInterface
{
    public function observe(EventAttacherInterface $attacher): void
    {
        $attacher->attach(
            Registered::class,
            fn(Registered $event) => $this->sendWelcomeEmailToUser($event)
        );

        $attacher->attach(
            Registered::class,
            fn(Registered $event) => $this->sendNewUserEmailToAdmin($event)
        );
    }
}
```

#### **Priority-Based Event Handling**
Events can be handled with different priorities:

```php
$attacher->attach(
    LifecycleEventInterface::class,
    function (Event $event): void {
        $this->occurredTimes[$event::class] = microtime(true);
    },
    -1
);
```

**Event Handler Priority System**:
- **Numbers**: The larger the number, the higher the priority
- **Zero**: Default priority

#### **Event Features**
- **Type-safe events** with strict typing
- **Priority-based handling** for event ordering
- **Multiple observers** per event type
- **Interface-based observables** for flexibility
- **Automatic event registration** through observers
- **Queueable events** for asynchronous processing with `CanQueueObservables`
- **Lifecycle monitoring** with benchmark capabilities
- **Dual priority systems**: Event handler priority and queue processing priority

### 3. Attribute-Based Routing

Modern routing system using PHP 8 attributes:

```php
#[Route(
    route: '/auth/login',
    httpMethod: HttpMethod::POST,
    middlewareClasses: [CsrfTokenMiddleware::class]
)]
final readonly class LoginSubmitController implements ControllerInterface
{
    #[Request(LoginRequest::class)]
    public function handle(RequestInterface $request): ResponseInterface
    {
        // Controller logic
    }
}
```

### 4. Middleware Chain System

Flexible middleware implementation with chain-of-responsibility pattern:

- **Automatic middleware chaining**
- **Priority-based execution**
- **Route-specific middleware**
- **Global middleware support**

### 5. Advanced Request Validation System

Comprehensive validation framework with multiple layers of validation:

#### **Request-Level Validation**
Requests can be validated using the `ValidatableRequest` base class:

```php
final readonly class LoginRequest extends ValidatableRequest
{
    protected function validatorClass(): string
    {
        return LoginValidator::class;
    }

    public function email(): string
    {
        return (string) $this->getPayload()->get('email');
    }

    public function password(): string
    {
        return (string) $this->getPayload()->get('password');
    }
}
```

#### **Validator Chain System**
Chain-based validation with multiple validator types:

```php
final readonly class LoginValidator extends MapValidator
{
    public function __construct(
        EmailValidator $emailValidator,
        FilledValidator $notEmptyValidator,
    ) {
        parent::__construct([
            'email' => new ValidatorChain([
                $notEmptyValidator,
                $emailValidator,
            ]),
            'password' => $notEmptyValidator,
        ]);
    }
}
```

#### **Controller Integration**
Automatic validation with flash message integration:

```php
#[Request(LoginRequest::class)]
public function handle(RequestInterface $request): ResponseInterface
{
    if (!$this->valid($request)) {
        return new RedirectResponse('/login');
    }
    
    // Process validated request
    return $this->login($request);
}
```

#### **Available Validators**
- **FilledValidator**: Ensures non-empty string values
- **EmailValidator**: Validates email format
- **ExistsValidator**: Checks database existence
- **ListValidator**: Validates array lists
- **MapValidator**: Validates associative arrays
- **ValidatorChain**: Combines multiple validators

#### **Validation Features**
- **Type-safe validation** with strict typing
- **Custom error messages** with field name substitution
- **Flash message integration** for user feedback
- **Extensible validator system** for custom rules
- **Automatic validation** in controllers

### 6. Extensible Queue System

Advanced job queuing system with multiple backend support:

#### **Queue Interface Architecture**
The framework provides an extensible queue system through the `QueueInterface`:

```php
interface QueueInterface
{
    public function append(QueueableInterface $queueable): void;
    public function pop(): ?QueueableInterface;
}
```

#### **Built-in Queue Implementations**
- **DatabaseQueue**: Persistent database-backed queuing (currently implemented)
- **Extensible Factory**: Easy addition of custom queue backends

#### **Priority-Based Job Processing**
Jobs support priority levels for processing order:

```php
enum QueuePriority: int
{
    case Low = 0;
    case Medium = 1;
    case High = 2;
}
```

#### **Job Implementation**
Jobs extend the base `Job` class with automatic queuing support:

```php
final readonly class EmailJob extends Job
{
    public function __construct(
        public string $email,
        public string $subject,
        public string $content
    ) {}

    public function getQueuePriority(): QueuePriority
    {
        return QueuePriority::Low;
    }

    public function execute(EmailManager $emailManager): void
    {
        $emailManager->send($this->email, $this->subject, $this->content);
    }
}
```

#### **Easy Job Queuing**
Static queuing method for convenient job dispatch:

```php
EmailJob::queue($email, $subject, $content);
```

#### **Extensible Queue Backends**
The factory-based architecture allows easy addition of custom queue systems. The following could be implemented by extending `QueueInterface`:

- **Redis Queue**: For high-performance in-memory queuing (not implemented)
- **RabbitMQ Queue**: For distributed message processing (not implemented)
- **SQS Queue**: For cloud-based queuing (not implemented)
- **Custom Queue**: Any queue system implementing `QueueInterface`

#### **Queue Features**
- **Priority-based processing** with configurable priorities
- **Automatic dependency injection** for job execution
- **Command pattern** implementation
- **Factory-based instantiation** for easy backend switching
- **Type-safe job handling** with strict typing
- **Extensible architecture** for custom queue implementations
- **Queueable events** with `CanQueueObservables` trait
- **Asynchronous event processing** for performance optimization

#### **Transactions**
`TransactionManagerInterface` bietet verschachtelbare Transaktionen (Savepoints ab der zweiten Ebene) und `afterCommit`-Hooks:

```php
$this->transactionManager->transactional(function (): void {
    $this->userService->create($user);
    Registered::queue($user->email, $user->firstName, $user->lastName); // landet atomar mit dem User in der DB
});

$this->transactionManager->afterCommit(fn() => $this->notifyExternalSystem()); // läuft erst nach dem äußersten Commit
```

- Exception im Callback → Rollback der jeweiligen Ebene, Exception wird weitergeworfen
- `afterCommit`-Callbacks einer zurückgerollten Ebene verfallen; ohne Transaktion laufen sie sofort

#### **Transactional Outbox**
Ist `database.outbox.enabled` gesetzt, schreibt `QueueInterface::append()` (und damit `Job::queue()` / `Event::queue()`) zunächst in die Tabelle `outbox_messages` – in derselben Transaktion wie die Fachdaten. Der Relay leitet die Nachrichten anschließend an den konfigurierten Transport (Default: `DatabaseQueue`) weiter:

```
Request:  Fachdaten + outbox_messages  (eine Transaktion)
Cron/Worker:  php bin/console.php outbox:relay   → Transport (queue_items)
Worker:       php bin/console.php dequeue        → Job/Event ausführen
```

```php
// config/database.php
'outbox' => [
    'enabled' => true,
    'transport' => DatabaseQueue::class, // später z. B. eine Redis-/RabbitMQ-Queue
    'batchSize' => 100,
    'maxAttempts' => 5, // danach bleibt die Nachricht mit last_error liegen
],
```

- Liegt der Transport in derselben Datenbank, sind Claim und Weiterleitung atomar (kein Verlust, keine Duplikate)
- Bei externen Transporten gilt **at-least-once**: Consumer müssen idempotent sein
- Das Schema liegt unter `framework/database/schema/*.sql` und muss einmalig angewendet werden:
  `sqlite3 data/database.db < framework/database/schema/outbox_messages.sql`
- Tests: `composer test`

### 7. Console Command System

Attribute-based command-line interface:

```php
#[Command('dequeue')]
final readonly class DequeueCommand implements CommandInterface
{
    public function run(array $arguments, ConsoleOutputInterface $output): void
    {
        // Command implementation
    }
}
```

### 8. Multi-Worker Application Support

The framework supports multiple application workers for different deployment scenarios:

- **Default Worker**: Traditional request-per-process handling
- **FrankenPHP Worker**: High-performance Go-based PHP server integration
- **Extensible Architecture**: Easy addition of custom workers

```php
// Using FrankenPHP worker for high-performance deployment
$application = new Application(
    new FactoryManager(),
    [AppBootstrapper::class],
    [CsrfTokenMiddleware::class],
    FrankenPHPWorker::class
);

// FrankenPHP deployment with Docker
docker run --rm \
    -e FRANKENPHP_CONFIG="worker /app/public/index.php" \
    -v $PWD:/app \
    -p 80:80 -p 443:443 -p 443:443/udp \
    dunglas/frankenphp
```

The FrankenPHP worker provides:
- **Request recycling** for improved performance
- **Memory management** with automatic flushing
- **Lifecycle events** for worker monitoring
- **Configurable request limits** via `MAX_REQUESTS` environment variable

## 🛠️ Technical Stack

### Core Dependencies
- **PHP 8.5+** with strict typing and modern features
- **PDO** for database operations
- **Flow PHP Array Dot** for configuration management
- **Vlucas PHP Dotenv** for environment configuration

### Development Tools
- **PHPStan** (Level 9) for static analysis
- **Psalm** for type checking
- **Deptrac** for architecture enforcement
- **PHP CS Fixer** for code style
- **Rector** for code migration
- **Whoops** for error handling
- **Symfony Var Dumper** for debugging

## 📁 Project Structure

```
app/
├── framework/                # Core framework implementation
│   ├── src/
│   │   ├── Application/      # Application lifecycle management
│   │   ├── Bootstrappers/    # Bootstrapping system
│   │   ├── Configurations/   # Configuration management
│   │   ├── Consoles/         # CLI command system
│   │   ├── Databases/        # Database abstraction
│   │   ├── Dependencies/     # Dependency resolution
│   │   ├── Emails/           # Email handling
│   │   ├── Events/           # Event system
│   │   ├── Factories/        # Factory pattern implementation
│   │   ├── FactoryManagers/  # Dependency injection container
│   │   ├── Http/             # HTTP layer (requests, responses, routing)
│   │   ├── Jobs/             # Job queuing system
│   │   ├── Listeners/        # Event listeners
│   │   ├── Models/           # Model interfaces
│   │   ├── Providers/        # Service providers
│   │   ├── Queues/           # Queue implementations
│   │   ├── Sessions/         # Session management
│   │   ├── Validations/      # Validation framework
│   │   └── Views/            # View system
├── src/                      # Application code
│   ├── Bootstrappers/        # Application bootstrappers
│   ├── Events/               # Application events
│   ├── Http/                 # Controllers, requests, validators
│   ├── Listeners/            # Event listeners
│   ├── Models/               # Domain models
│   ├── Services/             # Business logic services
│   └── Validators/           # Custom validators
├── config/                   # Configuration files
├── views/                    # Template files
└── public/                   # Web entry point
```

## 🔧 Configuration

The framework uses a flexible configuration system:

```php
// config/database.php
return [
    'database' => [
        'dsn' => env('DATABASE_DSN', 'sqlite::memory:'),
        'username' => env('DATABASE_USERNAME'),
        'password' => env('DATABASE_PASSWORD'),
    ]
];
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.5 or higher
- Composer
- SQLite (or other PDO-compatible database)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd app
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

4. **Run the application**
   ```bash
   php -S localhost:8000 -t public
   ```

### Development Commands

```bash
# Code style
composer codestyle-fix
composer codestyle-check

# Static analysis
vendor/bin/phpstan analyse
vendor/bin/psalm

# Architecture analysis
vendor/bin/deptrac
```

## 🎯 Design Patterns Implemented

### 1. **Dependency Injection Pattern**
- Custom DI container with attribute-based configuration
- Autowiring with dependency resolution
- Factory pattern integration

### 2. **Observer Pattern**
- Event system with observable interfaces
- Priority-based event handling with negative numbers for higher priority
- Lifecycle event management with automatic firing
- Custom application events with queueable support
- Multiple observers per event type

### 3. **Chain of Responsibility**
- Middleware chain implementation
- Validation chain system with multiple validator types
- Request processing pipeline
- Field-level validation with MapValidator

### 4. **Factory Pattern**
- Factory manager with custom factories
- Attribute-based factory registration
- Instance management with request-scoped flushing
- Instance locking for persistent instances across requests

### 5. **Command Pattern**
- Console command system
- Job queuing implementation
- Request handler abstraction

### 6. **Template Method Pattern**
- Abstract base classes with defined algorithms
- `Event` base class with `CanOccur` trait
- `Job` base class with `CanQueue` trait
- `Middleware` base class with chain handling

### 7. **Strategy Pattern**
- Application worker selection
- Multiple deployment strategies
- Runtime worker configuration

### 8. **Flyweight Pattern**
- Instance caching through FactoryManager
- Shared object instances via `instance()` function
- Memory optimization for expensive objects
- Global instance access with type safety

### 9. **Decorator Pattern**
- Request decoration with `DecoratedRequest` base class
- `ValidatableRequest` extends `DecoratedRequest` for validation
- Request interface enhancement without modification
- Composition-based request enhancement

### 10. **Value Object Pattern**
- Extensive use of `final readonly` classes
- Immutable data structures throughout the framework
- Type-safe value objects with strict typing
- Enum-based value objects (`QueuePriority`, `HttpMethod`)

### 11. **Provider Pattern**
- Service providers for framework components
- `RouteProvider`, `ObserverProvider`, `CommandProvider`
- Centralized registration of framework services
- Extensible provider system for custom components

### 12. **Builder Pattern**
- Application construction with configurable components
- Step-by-step bootstrapping process
- Framework and application bootstrapper separation
- Fluent interface for application configuration

## 🔒 Security Features

- **CSRF Protection** with token validation
- **Authentication Middleware** for route protection
- **Advanced Input Validation** with multi-layer validation system
- **Type-Safe Request Handling** with strict validation
- **Session Management** with flash messages
- **Request Decoration** for enhanced security
- **Field-Level Validation** with custom error messages

## 📊 Code Quality

The project maintains high code quality standards:

- **PHPStan Level 9** static analysis
- **Psalm** type checking with strict configuration
- **Deptrac** architecture enforcement
- **PHP CS Fixer** code style compliance
- **Comprehensive test coverage** (framework design supports testing)

## 🌟 Advanced Features

### 1. **Comprehensive Event System**
Complete event-driven architecture with multiple event types:

- **Lifecycle Events**: Automatic application lifecycle monitoring
- **Custom Events**: Domain-specific business logic events
- **Queueable Events**: Asynchronous event processing with `CanQueueObservables`
- **Priority-Based Handling**: Event ordering with dual priority systems
- **Benchmark Events**: Performance monitoring capabilities
- **Observer Registration**: Automatic event listener registration
- **Asynchronous Processing**: Events can be queued for background processing

### 2. **Application Builder Pattern**
Sophisticated application construction with step-by-step bootstrapping:

- **Framework Bootstrapper**: Core framework component registration
- **Application Bootstrapper**: Application-specific configuration
- **Finalize Bootstrapper**: Service provider registration and finalization
- **Configurable Components**: Factory manager, middleware, workers
- **Fluent Interface**: `$application->boot()->run()` chaining

### 3. **Multi-Worker Deployment Support**
Flexible deployment architecture supporting multiple application workers:

```php
// Traditional deployment with default worker
$application = new Application(
    new FactoryManager(),
    [AppBootstrapper::class],
    [CsrfTokenMiddleware::class]
);

// High-performance FrankenPHP deployment
$application = new Application(
    new FactoryManager(),
    [AppBootstrapper::class],
    [CsrfTokenMiddleware::class],
    FrankenPHPWorker::class
);

$application->boot()->run();
```

**Note**: The `InstanceLocked` attribute is crucial for e.g. FrankenPHP workers to prevent critical instances (like Router, ViewGenerator) from being flushed between requests, ensuring optimal performance.

### 4. **Lifecycle Management**
Complete application lifecycle with event-driven bootstrapping and worker-specific events:

### 5. **Flexible View System**
Template engine with view helpers and data injection:

```php
$viewGenerator->generate('home', ['user' => $user]);
```

### 6. **Database Abstraction**
PDO-based database manager with model support:

```php
$databaseManager->save($user);
$users = $databaseManager->select(User::class);
```

### 7. **Queue System**
Extensible job queuing with multiple backend support:

```php
// Easy job queuing with static method
EmailJob::queue($email, $subject, $content);

// Custom queue backend support
$jobManager->queue(new EmailJob($user));
```

## 🎨 Showcase Application

The framework includes a complete web application demonstrating:

- **User Authentication** (login/register/logout)
- **Form Validation** with custom validators
- **Flash Messages** for user feedback
- **Responsive Design** with modern UI
- **Security Features** (CSRF, authentication)

## 🔮 Future Enhancements

The framework architecture supports easy extension:

- **Caching Layer** with multiple backends
- **API Support** with JSON responses
- **Database Migrations** system
- **Testing Framework** integration
- **Plugin System** for modular extensions
- **Additional Queue Backends** (Redis, RabbitMQ, SQS) - easily implementable via `QueueInterface`
- **Queue Monitoring** and management tools

## 📈 Performance Considerations

- **Lazy Loading** of dependencies
- **Memory Management** with automatic flushing between requests
- **Instance Locking** for persistent instances across requests (critical for FrankenPHP)
- **Flyweight Pattern** for instance caching and memory optimization
- **Efficient Routing** with constraint-based matching
- **Optimized Event Handling** with priority sorting
- **Multi-Worker Support** for different deployment scenarios
- **Request Recycling** with FrankenPHP for improved performance
- **Configurable Request Limits** via `MAX_REQUESTS` environment variable for worker recycling

## 🤝 Contributing

This project demonstrates advanced PHP framework development skills including:

- **Modern PHP 8 Features** (attributes, readonly classes, union types)
- **SOLID Principles** implementation
- **Design Patterns** mastery
- **Architecture Design** expertise
- **Code Quality** standards
- **Security Best Practices**

## 🎓 Mentorship & Learning

This framework serves as an **excellent mentorship tool** for:

- **Apprentices**: Learning enterprise-level software development
- **Junior Developers**: Understanding advanced architectural concepts
- **Code Review Training**: Comprehensive codebase for review practice
- **Architecture Discussions**: Real-world examples of design decisions
- **Best Practices**: Industry-standard development practices and tooling

The project's complexity and comprehensive documentation make it ideal for **hands-on learning** and **mentorship programs**.

## 📄 License

This project is a showcase of custom framework development skills and architectural expertise.

---

**Built with ❤️ using modern PHP practices and advanced software architecture principles.**
