# ADR-0001: Project Scope and Responsibility Boundary

- Status: Accepted
- Date: 2026-08-19

## Context

Payflow is a payment lifecycle and scheduling API.

The system needs to manage payments throughout their lifecycle and communicate with an external system responsible for performing the actual payment processing.

The external processor is not part of this project.

A key architectural concern is avoiding coupling the payment domain to a specific processor implementation.

Payflow should know what information is required to request payment processing and how to interpret the result, but it should not need to know who performs the processing or how the processing is implemented.

## Decision

Payflow will own the payment lifecycle and the integration contracts required to communicate with an external payment processor.

Payflow will not own the implementation of the payment processor.

The external processor is treated as an implementation outside the Payflow system boundary.

Communication between Payflow and the external processor will occur through an asynchronous messaging boundary.

Payflow is responsible for:

- Creating and managing payments
- Scheduling payments
- Maintaining payment state
- Recording payment history
- Managing processing attempts
- Supporting reprocessing
- Ensuring idempotency
- Publishing processing requests
- Consuming processing results
- Defining and versioning messaging contracts
- Providing observability for the payment lifecycle

The external processor is responsible for performing the actual payment processing.

## Responsibility Boundary

The responsibility boundary can be represented as:

~~~text
                         Payflow
                            |
          +-----------------+-----------------+
          |                                   |
          v                                   v
     Payment Domain                     Integration
          |                              Contracts
          |                                   |
          +-----------------+-----------------+
                            |
                        RabbitMQ
                            |
                            v
                  External Processor
~~~

Payflow owns everything on the left side of the integration boundary.

The external processor owns everything related to the actual execution of the payment.

The messaging contract is the agreement between the two systems.

## Rationale

Separating payment lifecycle management from payment execution allows the payment domain to remain independent from a specific processing implementation.

The processor may change without requiring the payment domain to be redesigned.

The external processor may also be implemented, deployed or scaled independently from Payflow.

This separation also makes it possible to develop and test Payflow without requiring the actual payment processor to be available.

## Consequences

### Positive

- The payment domain remains independent from the processing implementation.
- The external processor can evolve independently.
- The processor can potentially be replaced without redesigning the payment domain.
- Messaging contracts become explicit architectural boundaries.
- Payflow can be developed and tested independently from the external processor.
- Different processing implementations can potentially consume the same contract.
- Asynchronous processing allows the external processor to operate independently from the API request lifecycle.

### Negative

- Processing becomes asynchronous.
- The system must handle eventual consistency.
- Message duplication must be expected.
- Message correlation becomes necessary.
- Failures can occur after Payflow has already accepted a request.
- Integration contract evolution requires explicit versioning.
- Observability must cross asynchronous boundaries.
- Additional infrastructure is required for messaging.

## Alternatives Considered

### Directly Integrate Payflow With the Processor

Rejected because it would couple the API directly to a specific processor implementation.

This would make changes to the processor more likely to affect the payment domain.

It would also increase the responsibility of the Payflow API by making it responsible for details related to payment execution.

### Perform Payment Processing Inside Payflow

Rejected because payment lifecycle management and payment execution are separate responsibilities in this project.

Keeping them separate provides a clearer domain boundary and allows the processor to evolve independently.

### Synchronous HTTP Integration

Rejected as the primary integration mechanism because the project is intentionally designed around asynchronous payment processing.

The payment request should not depend on the external processor completing its work during the original API request.

## Scope

This decision establishes the initial system boundary.

It does not define the detailed implementation of:

- Payment states
- Idempotency
- Scheduling
- Retry policies
- RabbitMQ topology
- Transactional outbox
- Database schema
- Error taxonomy
- Observability implementation

Those concerns will be evaluated independently and documented through additional decisions when appropriate.

## Related Documentation

- [Architecture Overview](../architecture/overview.md)
- [Messaging Contracts](../contracts/messaging.md)