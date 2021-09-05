SHELL := /bin/bash

.PHONY: tests

tests: export APP_ENV=test
tests:
	php console db:setup
	php console migrations:reset --seed
	php console tests:run