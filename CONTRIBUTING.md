# Contributing

## Running the tests

### Unit tests

These tests also run in CI. The purpose of these tests is to aid the design and to get a good code coverage.

`composer test:unit`

#### To collect coverage stats

`composer test:coverage`

## Integration tests

These are run offline at the developers request. The purpose of these tests is to check it works as is expected in a working WordPress setup.

### Setup

`cp tests/Integration/.env.dist tests/Integration/.env`

> Update details accordingly

`tests/Integration/create-test-db.sh`

### Running the tests

`composer test:integration`

> Due to requirement of wp-phpunit which only works with PHPUnit 7, you will need to ensure you are using phpunit:^7 for running the integration tests. You can force this with `composer update phpunit/phpunit:^7 -W`
