test:
	@php console test

dc-up:
	docker-compose up

lint:
	./vendor/bin/phpstan analyse --memory-limit=2G

format:
	./vendor/bin/php-cs-fixer fix --show-progress=dots .

serve:
	@php console serve

reset-db:
	@php console migrations:reset --seed
