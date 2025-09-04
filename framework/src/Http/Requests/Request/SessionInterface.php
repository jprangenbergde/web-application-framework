<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Request;

use Framework\Interfaces\GettableInterface;
use Framework\Interfaces\SettableInterface;
use Framework\Interfaces\UnsettableInterface;

interface SessionInterface extends GettableInterface, SettableInterface, UnsettableInterface {}
