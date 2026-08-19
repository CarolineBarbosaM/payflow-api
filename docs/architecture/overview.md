# Architecture Overview

## Purpose

This document describes the high-level architecture and responsibility boundaries of Payflow.

The architecture is intentionally documented at a level that allows implementation details to evolve without changing the core responsibilities of the system.

## System Boundary

Payflow is responsible for managing the lifecycle of payments.

It does not perform the actual financial processing.

The system responsible for executing the payment is external to Payflow.

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

The external processor is outside the Payflow system boundary.

Payflow does not depend on:

- Its programming language
- Its framework
- Its internal architecture
- Its deployment model
- Its internal processing rules

The communication boundary is defined through explicit contracts.

## Core Responsibilities

### Payment Lifecycle

Payflow owns the lifecycle of a payment from creation through its final state.

The lifecycle includes the management of:

- Payment creation
- Payment state
- Processing attempts
- Successful processing
- Failed processing
- Reprocessing
- Payment history

The exact state model will be defined as part of the domain design.

### Scheduling

Payflow manages scheduled and recurring payment rules.

A schedule may generate payment occurrences according to its configured rules.

Scheduling is part of the Payflow domain because the API is responsible for determining when a payment should enter its processing lifecycle.

The detailed scheduling model will be defined separately.

### Processing Attempts

Each execution of a payment processing request is represented as an independent processing attempt.

This allows the system to distinguish between the payment itself and each individual attempt to process it.

~~~text
Payment
    |
    +-- Attempt #1
    |
    +-- Attempt #2
    |
    +-- Attempt #3
~~~

This distinction is important for:

- Failure handling
- Reprocessing
- Retry policies
- Auditing
- Observability

### History

Payment state transitions and relevant lifecycle events are recorded as history.

History provides an auditable representation of what happened to a payment over time.

History is not intended to replace the current payment state. The payment represents the current state of the lifecycle, while history represents how that state was reached.

### Messaging

Payflow is both a producer and a consumer of asynchronous messages.

It publishes processing requests and consumes processing results.

The messaging layer acts as the integration boundary between Payflow and the external processor.

## Domain Model

The initial domain is centered around:

~~~text
Schedule
    |
    | generates
    v
Payment
    |
    +---- PaymentHistory
    |
    +---- ProcessingAttempt
~~~

Additional concepts may be introduced as the domain evolves.

The domain model should represent business concepts and rules rather than infrastructure concerns.

## Messaging Boundary

Payflow communicates with the external processor asynchronously.

The API publishes:

~~~text
PaymentProcessingRequested
~~~

The API consumes:

~~~text
PaymentProcessingSucceeded
PaymentProcessingFailed
~~~

Messages contain explicit versions and correlation information.

The message contract is independent from the implementation details of the transport.

RabbitMQ is the initial transport selected for the project.

The detailed message schemas and compatibility rules are documented separately in the messaging contracts.

## Consistency

The payment database is the source of truth for the payment lifecycle.

Messaging introduces asynchronous processing and therefore eventual consistency between Payflow and the external processor.

The architecture must explicitly address:

- Idempotency
- Duplicate messages
- Message correlation
- Transactional boundaries
- Retry behavior
- Failure handling
- Reprocessing
- Message delivery guarantees

The detailed strategies will be documented through separate Architecture Decision Records as they are defined.

## Observability

Observability is part of the system architecture rather than an operational afterthought.

The initial observability strategy includes:

- Structured application logs
- Correlation IDs
- Distributed tracing
- Metrics
- RabbitMQ metrics
- Payment processing metrics
- Processing latency measurements
- Failure and retry measurements

The planned observability architecture is:

~~~text
                         Application
                              |
                +-------------+-------------+
                |             |             |
                v             v             v
             Logs          Traces        Metrics
                |             |             |
                |        OpenTelemetry      |
                |             |             |
                |             v             |
                |           Jaeger          |
                |                           |
                |                         Prometheus
                |                           |
                +-------------+-------------+
                              |
                              v
                           Grafana
~~~

Correlation IDs and trace IDs will be used to connect activity across asynchronous processing boundaries.

Correlation and tracing serve different purposes:

- Correlation ID identifies a business or integration flow.
- Trace ID identifies a distributed execution trace.

They may be associated with each other but are not considered the same concept.

## Technology Boundaries

The initial technology stack is:

### Application

- PHP 8.4+
- Laravel 12

### Persistence

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

Technology choices are implementation decisions and may evolve.

The architectural responsibility boundaries should remain independent from specific infrastructure choices whenever possible.

## Architectural Principles

The initial architecture follows these principles:

### Separation of Responsibilities

Each component should have a clearly defined responsibility.

### Domain Independence

Business rules should not depend directly on infrastructure concerns.

### Explicit Integration Contracts

Communication with external systems must happen through explicit and versioned contracts.

### Asynchronous Processing

Long-running or externally dependent payment processing should not require synchronous coupling between systems.

### Idempotent Operations

Operations that may be retried or delivered more than once must be designed to produce safe and predictable results.

### Observable Workflows

Important business and technical operations must be observable across synchronous and asynchronous boundaries.

### Evolution Through Decisions

Important architectural decisions should be documented through Architecture Decision Records.

## Evolution

This document describes the current architectural direction and is expected to evolve with the project.

New architectural decisions should be documented when they materially affect:

- System boundaries
- Domain responsibilities
- Data consistency
- Integration contracts
- Infrastructure
- Reliability
- Security
- Observability
- Scalability

Architecture documentation should describe decisions that have actually been made and should not be used to prematurely lock unresolved design questions.