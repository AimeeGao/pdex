# OAuth API Authentication Implementation

This implementation provides OAuth 2.0 Client Credentials flow authentication for PDEX API endpoints using Laravel Sanctum and custom OAuth token validation.

## Overview

The system uses OAuth 2.0 Client Credentials flow to authenticate API requests:

1. **Token Acquisition**: Get access tokens from the OAuth server using client credentials
2. **Token Validation**: Validate Bearer tokens on each API request
3. **Audience Validation**: Ensure tokens have the correct audience claim
4. **Caching**: Token caching for performance optimization

## Environment Configuration

Required environment variables in `.env`:

```bash
APP_API_CLIENT_ID=F603ABCA-53E5E7C54C0
APP_API_CLIENT_SECRET=7ddae9d1-740e-4e2c-b849-061b72512ab3
APP_API_TOKEN_ENDPOINT=https://dev.loginproxy.gov.bc.ca/auth/realms/apigw/protocol/openid-connect/token
APP_API_AUDIENCE=ap-pdex-fe0394-default-dev
```

## API Endpoints

All API endpoints under `/api/v1/` are protected with OAuth authentication:

- `GET /api/v1/test` - Test endpoint for OAuth validation
- `GET /api/v1/students` - Students data endpoint
- `GET /api/v1/institutions` - Institutions data endpoint
- `GET /api/v1/applications` - Applications data endpoint

## Usage Examples

### 1. Getting an Access Token

```bash
curl -X POST "https://dev.loginproxy.gov.bc.ca/auth/realms/apigw/protocol/openid-connect/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "grant_type=client_credentials" \
  -d "client_id=F603ABCA-53E5E7C54C0" \
  -d "client_secret=7ddae9d1-740e-4e2c-b849-061b72512ab3" \
  -d "audience=ap-pdex-fe0394-default-dev"
```

### 2. Using the Token in API Requests

```bash
curl -X GET "http://127.0.0.1:8232/api/v1/test" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Accept: application/json"
```

### 3. Using PHP Service Class

```php
use App\Services\OAuthTokenService;

$oauthService = app(OAuthTokenService::class);

// Get access token
$token = $oauthService->getAccessToken();

// Make authenticated API request
$response = Http::withToken($token)
    ->get('http://127.0.0.1:8232/api/v1/students');
```

## Testing

Use the built-in Artisan command to test the OAuth implementation:

```bash
# Test the main test endpoint
php artisan oauth:test --endpoint=test

# Test other endpoints
php artisan oauth:test --endpoint=students
php artisan oauth:test --endpoint=institutions
php artisan oauth:test --endpoint=applications
```

## Components

### 1. OAuthTokenService
- **Location**: `app/Services/OAuthTokenService.php`
- **Purpose**: Handles token acquisition, validation, and caching
- **Methods**:
  - `getAccessToken()`: Obtain access token using client credentials
  - `validateToken($token)`: Validate and decode JWT token
  - `revokeToken($token)`: Revoke/invalidate token

### 2. ValidateOAuthToken Middleware
- **Location**: `app/Http/Middleware/ValidateOAuthToken.php`
- **Purpose**: Validates Bearer tokens on incoming API requests
- **Alias**: `oauth.token`

### 3. API Routes
- **Location**: `routes/api.php`
- **Protection**: All `/api/v1/*` routes use `oauth.token` middleware

### 4. Test Command
- **Location**: `app/Console/Commands/TestOAuthToken.php`
- **Command**: `oauth:test`
- **Purpose**: End-to-end testing of OAuth functionality

## Security Features

1. **Token Validation**: 
   - JWT signature validation (locally)
   - Expiration time (`exp`) validation
   - Not-before time (`nbf`) validation
   - Audience (`aud`) validation

2. **Caching**:
   - Access tokens cached until near expiration
   - Token validation results cached briefly
   - Configurable cache expiry with safety buffer

3. **Error Handling**:
   - Comprehensive error logging
   - Graceful fallback for invalid tokens
   - Clear error messages for API consumers

## Error Responses

### 401 Unauthorized - Missing Token
```json
{
  "error": "Unauthorized",
  "message": "Bearer token required"
}
```

### 401 Unauthorized - Invalid Token
```json
{
  "error": "Unauthorized",
  "message": "Invalid or expired token"
}
```

## Token Claims

Valid tokens will contain these claims:
- `aud`: Audience (must match `APP_API_AUDIENCE`)
- `exp`: Expiration timestamp
- `iat`: Issued at timestamp
- `iss`: Issuer URL
- `sub`: Subject identifier
- `azp`: Authorized party (client ID)

## Performance Considerations

1. **Token Caching**: Access tokens are cached for their lifetime minus 5 minutes
2. **Validation Caching**: Token validation results cached for 5 minutes
3. **Local JWT Validation**: Tokens validated locally without server round-trips
4. **Connection Pooling**: HTTP client reuses connections for token requests

## Monitoring and Logging

All OAuth operations are logged with appropriate levels:
- **Info**: Successful token operations
- **Warning**: Audience mismatches, suspicious activity
- **Error**: Token acquisition failures, validation errors

Log entries include context for debugging and monitoring.