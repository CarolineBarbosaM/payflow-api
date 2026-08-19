# Payflow API

> Payment lifecycle and scheduling API designed around transactional consistency, idempotent operations, asynchronous processing and observable distributed workflows.

## Overview

Payflow is a backend API responsible for managing the lifecycle of payment requests.

The API is not responsible for performing the financial transaction itself. Instead, it manages the payment domain and communicates with an external processing system through asynchronous messaging.

The external processor is intentionally treated as an implementation detail. Payflow defines the contracts required to request processing and receive processing results without depending on who or what performs the actual operation.

## Goals

The project is designed to explore and implement backend engineering concerns commonly found in payment systems:

- Payment lifecycle management
- Scheduled and recurring payments
- Idempotent operations
- Payment state transitions
- Processing attempts and reprocessing
- Transactional consistency
- Asynchronous messaging
- Contract versioning
- Distributed tracing
- Metrics and structured logging
- Fault handling and observability

## Responsibilities

Payflow is responsible for:

- Creating and managing payments
- Managing payment schedules
- Maintaining payment state
- Recording payment history
- Managing processing attempts
- Supporting payment reprocessing
- Ensuring idempotency
- Publishing payment processing requests
- Consuming processing results
- Maintaining integration contracts
- Providing observability across the payment lifecycle

The system that actually performs the payment processing is outside the scope of this project.

## Architecture

At a high level:

~~~text
                         Payflow API
                              |
             +----------------+----------------+
             |                                 |
             v                                 v
        Payment Domain                    Messaging
             |                            Contracts
             |                                 |
             +----------------+----------------+
                              |
                         RabbitMQ
                              |
                              v
                    External Processor
~~~

The processor may be implemented using any technology. Payflow only depends on the agreed messaging contracts.

More details are available in the [Architecture Overview](docs/architecture/overview.md).

## Domain

The initial domain is centered around four concepts:

~~~text
Schedule
    |
    | generates
    v
Payment
    |
    +---- PaymentHistory[]
    |
    +---- ProcessingAttempt[]
~~~

A payment also preserves the relevant recipient and destination information used for its processing.

The domain will evolve incrementally as requirements and architectural decisions are explored.

## Messaging

Payflow communicates asynchronously through RabbitMQ.

The initial integration contracts are:

### Outgoing

`PaymentProcessingRequested`

Published when a payment processing attempt needs to be executed.

### Incoming

`PaymentProcessingSucceeded`

Indicates that a processing attempt completed successfully.

`PaymentProcessingFailed`

Indicates that a processing attempt failed.

Message contracts are versioned and documented independently from the implementation.

See [Messaging Contracts](docs/contracts/messaging.md).

## Observability

Observability is considered part of the architecture rather than an operational afterthought.

The initial observability strategy includes:

- Structured application logs
- Correlation IDs
- Distributed tracing
- Metrics
- RabbitMQ-related metrics
- Payment processing metrics

The planned stack is:

~~~text
OpenTelemetry
     |
     +---- Traces ----> Jaeger
     |
     +---- Metrics ---> Prometheus
                           |
                           v
                        Grafana
~~~

Logging will initially use Laravel's logging infrastructure with structured context.

## Technology Stack

### Application

- PHP 8.4+
- Laravel 12

### Data

- PostgreSQL 17

### Messaging

- RabbitMQ

### API

- REST
- OpenAPI / Swagger

### Testing and Quality

- Pest
- PHPUnit
- PHPStan
- Larastan
- Laravel Pint

### Infrastructure

- Docker
- Docker Compose

### Observability

- OpenTelemetry
- Prometheus
- Grafana
- Jaeger

### CI

- GitHub Actions

Individual technologies may be replaced as the project evolves. Architectural decisions and their rationale are documented through ADRs.

## Documentation

### Architecture

- [Architecture Overview](docs/architecture/overview.md)

### Contracts

- [Messaging Contracts](docs/contracts/messaging.md)

### Architecture Decision Records

- [ADR-0001 — Project Scope and Responsibility Boundary](docs/decisions/0001-project-scope.md)

## Project Status

🚧 **Early development**

The project is currently in the architecture and domain-definition phase.

The implementation will evolve incrementally as architectural decisions are validated through code and tests.