#!/usr/bin/env bash
set -Eeu

echo "Starting post-bootstrap initialization..."

# Check if required environment variables are set
if [[ "${APP_USER:-}" && "${APP_PASSWORD:-}" && "${APP_DATABASE:-}" ]]; then
  echo "Creating user ${APP_USER}"
  if psql "$1" -w -c "CREATE USER \"${APP_USER}\" WITH LOGIN ENCRYPTED PASSWORD '${APP_PASSWORD}'"; then
    echo "User ${APP_USER} created successfully"
  else
    echo "Warning: Failed to create user ${APP_USER} (may already exist)"
  fi

  echo "Creating database ${APP_DATABASE}"
  if psql "$1" -w -c "CREATE DATABASE \"${APP_DATABASE}\" OWNER \"${APP_USER}\" ENCODING '${APP_DB_ENCODING:-UTF8}' LC_COLLATE = '${APP_DB_LC_COLLATE:-en_US.UTF-8}' LC_CTYPE = '${APP_DB_LC_CTYPE:-en_US.UTF-8}'"; then
    echo "Database ${APP_DATABASE} created successfully"
  else
    echo "Warning: Failed to create database ${APP_DATABASE} (may already exist)"
  fi
else
  echo "Skipping user creation (APP_USER, APP_PASSWORD, or APP_DATABASE not set)"
  echo "Skipping database creation (APP_USER, APP_PASSWORD, or APP_DATABASE not set)"
fi

echo "Post-bootstrap initialization completed successfully"
exit 0
