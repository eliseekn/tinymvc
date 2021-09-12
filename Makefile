SHELL := /bin/bash

.PHONY: tests

tests:
	php console db:delete
	php console db:create
	php console migrations:run
	php console tests:run