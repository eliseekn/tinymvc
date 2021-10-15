SHELL := /bin/bash

.PHONY: tests docker

tests:
	php console db:delete
	php console db:create
	php console migrations:run
	php console tests:run

docker:
	docker-compose up