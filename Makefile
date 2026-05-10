test:
	@php console test

lint:
	./vendor/bin/phpstan analyse --memory-limit=2G

format:
	./vendor/bin/php-cs-fixer fix --show-progress=dots .

serve:
	@php console serve

api-doc:
	@php console api:doc
