# ──────────────────────────────────────────────
# HDS Onderhoudsdiensten — Development Makefile
# ──────────────────────────────────────────────

.PHONY: help up down restart shell wp phpcs phpstan lint build logs clean

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

# ── Docker ──
up: ## Start the local Docker environment
	docker compose up -d

down: ## Stop Docker environment
	docker compose down

restart: down up ## Restart the environment

logs: ## Tail Docker logs
	docker compose logs -f

shell: ## Open a shell in the WordPress container
	docker compose exec wordpress sh

# ── WordPress ──
wp: ## Run WP-CLI in the container
	docker compose exec wordpress wp $(filter-out $@,$(MAKECMDGOALS))

install-wp: ## Install WordPress core (first time)
	docker compose exec wordpress wp core download --locale=nl_NL --allow-root
	docker compose exec wordpress wp core install \
		--url=http://hds.local \
		--title="HDS Onderhoudsdiensten" \
		--admin_user=hds_admin \
		--admin_password=admin123 \
		--admin_email=admin@hds.local \
		--skip-email \
		--allow-root

# ── Coding standards ──
phpcs: ## Run PHPCS
	composer phpcs

phpcbf: ## Auto-fix PHPCS issues
	composer phpcs:fix

phpstan: ## Run PHPStan
	composer phpstan

lint: ## Run all linters
	composer lint
	npm run lint

build: ## Build frontend assets
	npm run build

# ── Utility ──
clean: ## Remove build artifacts, caches
	rm -rf node_modules vendor dist build
	docker compose down -v

%: ## Fallback
	@:
