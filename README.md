<p align="center">
  <strong>Ministry of Post-Secondary Education and Future Skills</strong><br/>
  <br/><br/>
  <strong>Post Secondary Data Exchange - PDEX</strong><br/><br/>

  <img src="https://img.shields.io/badge/Lifecycle-Stable-97ca00" alt="Stable">
  <img src="https://img.shields.io/badge/License-Apache%202.0-blue" alt="Apache 2.0">
  <img src="https://img.shields.io/badge/Laravel-12.x-red" alt="Laravel 12.x">
  <img src="https://img.shields.io/badge/Vue.js-3.x-green" alt="Vue.js 3.x">

</p>

<p align="center">
  <strong>Team Members</strong>
</p>

<p align="center">

  | Project Lead   | Security Officer | Project Owner   |
  | -------------- | ---------------- | --------------- |
  | Kal Marsh      | David Malcolm    | Nino Samson |

</p>

## 📖 About PDEX

The Post Secondary Data Exchange (PDEX) is a comprehensive identity provider management system designed to facilitate secure data exchange between post-secondary institutions and government services in British Columbia. The platform provides centralized authentication and authorization services supporting multiple identity providers including BC Services Card (BCSC), BCeID, and IDIR.

### 🎯 Key Features

- **Multi-Identity Provider Support**: Seamless integration with BCSC, BCeID, and IDIR
- **Application Management**: Complete lifecycle management for registered applications
- **Role-Based Access Control**: Granular permissions for Security Officers, Privacy Officers, and Application Managers
- **Approval Workflow**: Two-stage approval process requiring both security and privacy clearance
- **API Management**: Secure API credential generation and management
- **Status Management**: Real-time application status control (Active/Inactive/Offline)
- **Audit Trail**: Comprehensive logging and event tracking
- **Responsive Design**: Modern Vue.js interface with Bootstrap styling

### 🏗️ System Architecture

PDEX follows a modular Laravel architecture with:

- **Admin Module**: Core administrative functionality
- **Event-Driven Design**: Extensible event/listener system
- **Form Request Validation**: Centralized validation logic
- **Policy-Based Authorization**: Fine-grained access control
- **Inertia.js Integration**: Seamless Vue.js/Laravel communication

## 🚀 Quick Start

### Prerequisites

- PHP 8.2+
- Node.js 18+
- PostgreSQL 13+
- Composer
- Docker (for containerized deployment)

### Local Development Setup

1. **Clone the repository**
   ```bash
   git clone [repository-url]
   cd pdex
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run dev
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

## 📋 Application Workflow

1. **Application Submission**: New applications are submitted with required documentation
2. **Security Review**: Security Officers review and approve/reject based on STRA
3. **Privacy Review**: Privacy Officers review and approve/reject based on PIA  
4. **Activation**: Once both approvals are complete, applications can be activated
5. **API Access**: Approved applications receive API credentials for system integration

## 🛠️ Development

### Code Structure

```
app/
├── Models/           # Eloquent models
├── Policies/         # Authorization policies
└── ...

Modules/Admin/
├── app/
│   ├── Events/       # Application events
│   ├── Http/
│   │   ├── Controllers/    # Admin controllers
│   │   └── Requests/       # Form request validation
│   ├── Listeners/    # Event listeners
│   └── Providers/    # Service providers
└── resources/
    └── assets/js/Pages/    # Vue.js components
```

### Running Tests

```bash
# Run PHP tests
php artisan test

# Run JavaScript tests  
npm run test

# Run with coverage
php artisan test --coverage
```

### Code Quality

```bash
# PHP CS Fixer
./vendor/bin/php-cs-fixer fix

# ESLint  
npm run lint

# PHPStan
./vendor/bin/phpstan analyse
```

## 🐳 Docker Deployment

### Development
```bash
docker-compose up -d
```

### Production
```bash
docker-compose -f docker-compose.prod.yml up -d
```

## Components

- **Openshift Hosting**
    - DEV: [https://[app-id].apps.silver.devops.gov.bc.ca/](https://[app-id].apps.silver.devops.gov.bc.ca/)

- **Laravel Framework**
    - Documentation: [https://laravel.com/docs](https://laravel.com/docs)

- **Vue.js**
    - Documentation: [https://vuejs.org/guide](https://vuejs.org/guide)

- **Patroni Database for App**

- **Patroni Database for Backups**
    - Documentation: [https://patroni.readthedocs.io/en/latest/](https://patroni.readthedocs.io/en/latest/)

- **SSO (Single Sign-On) App for Authentication**
    - BCSC, BCeID, and IDIR

- **SonarCloud Scanner**

## 📚 API Documentation

### Authentication
All API requests require authentication via API key:

```bash
curl -H "Authorization: Bearer your_api_key" \
     https://pdex.gov.bc.ca/api/endpoint
```

### Available Endpoints

- `GET /api/applications` - List applications
- `GET /api/applications/{id}/status` - Get application status

## 🔍 Monitoring & Logging

- **Event Logging**: Comprehensive event tracking system
- **Performance Monitoring**: Built-in Laravel monitoring
- **Error Tracking**: Integrated error reporting

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

### Coding Standards

- Follow PSR-12 for PHP code
- Use ESLint configuration for JavaScript
- Write comprehensive tests
- Document new features

## 📄 License

This project is licensed under the Apache 2.0 License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For technical support or questions:

- **Documentation**: Internal wiki and documentation
- **Issue Tracking**: GitHub Issues
- **Team Contact**: See team members table above

## 🗺️ Roadmap

- [ ] Enhanced reporting and analytics
- [ ] Mobile application support
- [ ] Advanced audit logging
- [ ] Integration with additional identity providers
- [ ] Automated testing improvements
- [ ] Performance optimizations
