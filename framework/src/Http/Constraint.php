<?php

declare(strict_types=1);

namespace Framework\Http;

enum Constraint: string
{
    case Digit = '\d';
    case Digits = '\d+';
    case Letter = '\w';
    case Word = '\w+';
    case AlphaNumericCharacters = '[A-Za-z0-9]+.*';
}
