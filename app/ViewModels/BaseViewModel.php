<?php

namespace App\ViewModels;

abstract class BaseViewModel
{
    protected $data = [];
    protected $errors = [];
    protected $loading = false;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    public function addError(string $key, string $message): void
    {
        $this->errors[$key] = $message;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }

    public function isLoading(): bool
    {
        return $this->loading;
    }

    public function setLoading(bool $loading): void
    {
        $this->loading = $loading;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'errors' => $this->errors,
            'loading' => $this->loading,
        ];
    }
}
