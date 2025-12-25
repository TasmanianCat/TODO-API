.PHONY: help install dev serve test clear

help:
	@echo "Available commands:"
	@echo "  make install    - Install all dependencies"
	@echo "  make dev        - Start development server"
	@echo "  make serve      - Start PHP dev server"
	@echo "  make assets     - Build frontend assets"
	@echo "  make test       - Run tests"
	@echo "  make clear      - Clear caches"

install:
	composer install
	npm install

dev: install
	npm run dev &
	php artisan serve --host=0.0.0.0 --port=8000

serve:
	php artisan serve --host=0.0.0.0 --port=8000

assets:
	npm run dev

test:
	php artisan test

clear:
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	php artisan cache:clear

run-dev: dev
