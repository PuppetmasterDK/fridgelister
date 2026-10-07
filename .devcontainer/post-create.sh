#!/usr/bin/env bash
# Runs once, when the codespace is created.
set -euo pipefail

echo "Installing FridgeLister and loading the demo data..."
composer setup

# The app runs behind GitHub's proxy. Let it trust the proxy, so links and
# redirects use the codespace's https address.
if ! grep -q '^TRUSTED_PROXIES=' .env; then
    echo 'TRUSTED_PROXIES=*' >> .env
fi

echo "Installing Claude Code..."
curl -fsSL https://claude.ai/install.sh | bash

echo
echo "Ready. Start the app with:  php artisan serve"
echo "Start your agent with:      claude"
