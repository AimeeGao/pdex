# OpenShift Environment Configuration

## Security Notice

**⚠️ IMPORTANT: Environment files in this directory contain sensitive deployment configuration and must not be committed to version control.**

## Setup Instructions

1. **Copy template files to create your environment files:**
   ```bash
   cp dev.env.example dev.env
   cp prod.env.example prod.env
   cp test.env.example test.env
   ```

2. **Update the values in each `.env` file with your actual configuration:**
   - Replace placeholder values like `your-namespace-dev` with actual values
   - Set correct user IDs, namespaces, and resource limits
   - Verify repository URLs and branch names

3. **Verify files are ignored:**
   ```bash
   git status --ignored
   ```
   The `.env` files should appear in the "Ignored files" section.

## Environment Files

- **dev.env** - Development environment configuration
- **prod.env** - Production environment configuration  
- **test.env** - Test environment configuration

## Security

- All `.env` files are automatically ignored by `.gitignore`
- Template files (`.env.example`) are tracked for reference
- Never commit actual environment files to version control
- Store sensitive values in OpenShift secrets when possible

## Makefile Usage

The Makefile in this directory uses these environment files to deploy to OpenShift.
Ensure your environment files are properly configured before running deployment commands.
