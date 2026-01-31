# DDD Shop

## Description

This project is an educational implementation of Domain-Driven Design (DDD) concepts.
It demonstrates Value Objects, Entities, and Aggregates with explicit validation,
immutability, and domain rules.

The project is implemented in PHP and covered with unit tests.

## Implemented Concepts

- Value Objects (immutable, validated in constructors)
- Entities with unique identity (UUID)
- Aggregates with root entities controlling state changes
- Domain rules and invariants
- Unit tests using PHPUnit

## Structure

- `src/` — domain logic (Value Objects, Entities, Aggregates)
- `tests/` — unit tests for domain behavior

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
composer install
```

## Run Tests

```bash
vendor/bin/phpunit
```

OR

```bash
composer test
```

## Notes

All Value Objects are immutable.

All state changes are controlled through aggregate root methods.
