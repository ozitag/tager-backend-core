<?php

namespace OZiTAG\Tager\Backend\Core\Validation\Contracts;

interface IRule
{
    public function passes(string $attribute, mixed $value): bool;

    public function message(): string;

    public function code(): string;
}
