# Messaging Contracts

## Purpose

This document defines the messaging contracts used by Payflow to communicate with external payment processing systems.

Payflow communicates asynchronously through RabbitMQ.

The external processor is outside the Payflow system boundary and may be implemented using any technology.

The messaging contract is the integration boundary between the systems.

## Messaging Model

Payflow acts as both a message producer and a message consumer.

~~~text
                         Payflow API
                              |
                +-------------+-------------+
                |                           |
                v                           v
             Publish                     Consume
                |                           |
                v                           ^
             RabbitMQ                       |
                |                           |
                +-------- External ----------+
                         Processor
~~~

Payflow publishes processing requests and consumes processing results.

## Message Types

The initial messaging model contains three business events.

### Outgoing

`PaymentProcessingRequested`

Published when a payment processing attempt is ready to be processed by the external processor.

### Incoming

`PaymentProcessingSucceeded`

Indicates that a payment processing attempt completed successfully.

### Incoming

`PaymentProcessingFailed`

Indicates that a payment processing attempt failed.

## Message Envelope

Messages use a common envelope to provide metadata required for identification, correlation and evolution.

Conceptual structure:

~~~json
{
  "eventId": "evt_123",
  "eventType": "PaymentProcessingRequested",
  "version": 1,
  "occurredAt": "2026-08-19T10:00:00Z",
  "correlationId": "corr_123",
  "payload": {}
}
~~~

### eventId

Unique identifier of the message.

The identifier allows consumers to recognize a message and safely handle duplicate deliveries.

### eventType

Identifies the business event represented by the message.

### version

Explicit version of the message contract.

Contract versions allow the message schema to evolve without silently breaking consumers.

### occurredAt

Timestamp representing when the event occurred.

The timestamp refers to the business event rather than the moment when a consumer receives the message.

### correlationId

Identifier used to correlate messages belonging to the same business or integration flow.

Correlation IDs are particularly important when a payment crosses asynchronous boundaries.

### payload

Contains the business data specific to the event.

The payload must contain the information required by the receiving system without exposing unnecessary internal implementation details.

## Outgoing Events

### PaymentProcessingRequested

This event is published when Payflow creates a processing attempt that must be executed by the external processor.

The message must contain enough information for the external processor to perform the requested operation.

The exact payload is intentionally not finalized yet.

Conceptually, the message will contain information related to:

- Payment identification
- Monetary value
- Currency
- Recipient
- Payment destination
- Processing attempt
- Relevant scheduling information
- Correlation information

The contract must not expose Payflow's internal database structure.

For example, the external processor should not receive an entire database entity simply because the entity exists internally.

The payload will be defined based on the actual processing requirements.

## Incoming Events

### PaymentProcessingSucceeded

This event is consumed by Payflow when the external processor successfully completes a processing attempt.

The message must contain enough information for Payflow to identify:

- The payment
- The processing attempt
- The processing result
- The time at which processing completed
- Any external reference required by the payment lifecycle

Conceptual structure:

~~~json
{
  "paymentId": "pay_123",
  "processingAttemptId": "attempt_123",
  "processedAt": "2026-08-19T10:00:03Z",
  "externalReference": "external_123"
}
~~~

The exact payload will be defined during the domain and integration design.

### PaymentProcessingFailed

This event is consumed by Payflow when the external processor cannot complete a processing attempt.

The message must provide enough information for Payflow to:

- Identify the payment
- Identify the processing attempt
- Record the failure
- Determine whether the failure may be retried
- Support the appropriate reprocessing behavior

Conceptual structure:

~~~json
{
  "paymentId": "pay_123",
  "processingAttemptId": "attempt_123",
  "failedAt": "2026-08-19T10:00:03Z",
  "error": {
    "code": "TIMEOUT",
    "message": "Processing timeout",
    "retryable": true
  }
}
~~~

The final error model will be defined as part of the failure and retry strategy.

## Contract Principles

### Explicit Versioning

Every message contract must have an explicit version.

Changes to a contract must consider compatibility with existing consumers and producers.

### Idempotency

Message delivery must be treated as potentially duplicated.

Consumers must be designed to safely process the same message more than once.

Idempotency rules will be defined as part of the payment processing architecture.

### Correlation

Every processing flow must provide enough information to associate related messages with the same payment and processing attempt.

### Minimal Payload

Messages should contain the information required by the receiving system.

Internal persistence models must not be exposed simply because they are available inside Payflow.

### Technology Independence

The message contract must not expose implementation details of the external processor.

The processor may use a different language, framework, database or infrastructure.

### Explicit Business Meaning

Messages represent meaningful business events or commands.

They should not be designed as generic database synchronization messages.

### Schema Evolution

Message contracts must be designed to evolve without unnecessarily breaking existing consumers.

Compatibility rules will be defined before the messaging implementation is finalized.

## Reliability Considerations

The messaging architecture must account for the possibility of:

- Duplicate delivery
- Message loss
- Consumer failures
- Producer failures
- Temporary processor unavailability
- Processing timeouts
- Out-of-order delivery
- Retry attempts
- Poison messages

The specific reliability mechanisms will be defined separately.

Potential mechanisms include:

- Publisher confirms
- Dead-letter queues
- Retry queues
- Message TTL
- Idempotent consumers
- Transactional outbox
- Explicit retry policies

No mechanism is considered finalized until its architectural trade-offs have been evaluated.

## Observability

Messages must carry sufficient metadata to allow asynchronous workflows to be traced and correlated.

At minimum, the messaging architecture must support:

- Event identification
- Correlation
- Distributed tracing
- Structured logging

The relationship between `correlationId` and OpenTelemetry trace information will be defined as part of the observability implementation.

## Contract Ownership

Payflow owns the contracts required for the integration boundary defined by this project.

This does not mean that Payflow controls the internal implementation of the external processor.

Both sides must agree on the contract required for interoperability.

## Status

The contracts documented here represent the current architectural direction.

The following aspects remain intentionally open for further design:

- Final payload schemas
- Field validation rules
- Error taxonomy
- Versioning strategy
- Compatibility policy
- Retry strategy
- Dead-letter strategy
- Delivery guarantees
- Outbox strategy
- Queue topology
- Exchange and routing-key conventions

These decisions should be made as the implementation progresses and documented when they become architectural decisions.