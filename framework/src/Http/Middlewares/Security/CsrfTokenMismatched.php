<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares\Security;

use RuntimeException;

final class CsrfTokenMismatched extends RuntimeException {}
