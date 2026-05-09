<?php

namespace App\Repositories\Contracts;

interface SettingRepositoryInterface
{
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value): void;
    public function setMany(array $settings): void;
    public function allAsArray(): array;
    public function clearCache(?string $key = null): void;
    public function isEnabled(string $key): bool;
}
