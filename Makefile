SHELL := /bin/bash

.PHONY: tests

tests: export APP_ENV=test
tests:
	php console db:delete
	php console db:create
	php console migrations:run
	php console tests:run