db-test:
	php console app:env test
	php console db:delete
	php console db:create
	php console migrations:run

test:
	php console test

docker-up:
	docker-compose up
