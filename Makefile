test:
	php console app:env test
	php console db:delete
	php console db:create
	php console migrations:run
	php console tests:run

docker:
	docker-compose up
