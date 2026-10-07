#!/usr/bin/env bash
# Runs when the machine is built and whenever the code changes (also in a
# Codespaces prebuild). Installs FridgeLister and loads the demo data.
set -euo pipefail

composer setup

# The app runs behind GitHub's proxy. Let it trust the proxy, so links and
# redirects use the codespace's https address.
if ! grep -q '^TRUSTED_PROXIES=' .env; then
    echo 'TRUSTED_PROXIES=*' >> .env
fi
