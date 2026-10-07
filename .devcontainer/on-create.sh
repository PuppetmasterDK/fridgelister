#!/usr/bin/env bash
# Runs once, when the machine is built (also in a Codespaces prebuild).
set -euo pipefail

echo "Installing Claude Code..."
curl -fsSL https://claude.ai/install.sh | bash
