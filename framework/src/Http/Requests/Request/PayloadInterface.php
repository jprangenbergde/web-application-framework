<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Request;

use Framework\Interfaces\GettableInterface;
use Framework\Interfaces\ToArrayInterface;

interface PayloadInterface extends GettableInterface, ToArrayInterface {}
