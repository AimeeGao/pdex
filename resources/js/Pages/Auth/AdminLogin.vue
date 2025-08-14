<script>
import GuestLayout from '@/Layouts/Guest.vue';
import { Head } from '@inertiajs/vue3';

export default {
    name: 'AdminLogin',
    components: {
        GuestLayout, 
        Head
    },
    props: {
        loginAttempt: Boolean,
        hasAccess: Boolean,
        status: String,
        errors: Object,
    },
    computed: {
        errorMessage() {
            // Check for errors from middleware redirects
            if (this.$page.props.errors && this.$page.props.errors.error) {
                return this.$page.props.errors.error;
            }
            return null;
        }
    }
}
</script>

<style scoped>
.login-container {
    max-width: 500px;
    margin: 0 auto;
}

.btn-bc-gov {
    color: #fff;
    background-color: #003366;
    border-color: #003366;
    font-weight: 400;
}

.btn-bc-gov:hover {
    color: #fff;
    background-color: #002244;
    border-color: #002244;
}

.admin-card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #003366;
    margin-bottom: 1rem;
    text-align: center;
}

.admin-card-text {
    color: #6c757d;
    margin-bottom: 1.5rem;
    line-height: 1.5;
    text-align: center;
}

.back-link {
    color: #003366;
    text-decoration: none;
    font-size: 0.9rem;
}

.back-link:hover {
    color: #002244;
    text-decoration: underline;
}
</style>

<template>
    <GuestLayout>
        <Head title="Admin Login" />

        <div class="login-container">
            <!-- Error Alert -->
            <div v-if="errorMessage" class="alert alert-danger d-flex align-items-center mb-4">
                <i class="bi bi-x-circle me-2"></i>
                <span class="fw-medium">{{ errorMessage }}</span>
            </div>

            <!-- Status Alert (for authentication failures) -->
            <div v-if="status && loginAttempt && !hasAccess" class="alert alert-danger d-flex align-items-center mb-4">
                <i class="bi bi-x-circle me-2"></i>
                <span class="fw-medium">{{ status }}</span>
            </div>

            <!-- Success Status Alert -->
            <div v-else-if="status && !loginAttempt" class="alert alert-success d-flex align-items-center mb-4">
                <i class="bi bi-check-circle me-2"></i>
                <span>{{ status }}</span>
            </div>

            <!-- Admin Access Section -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="admin-card-title">
                        <i class="bi bi-shield-lock me-2"></i>Administrative Access
                    </div>
                    
                    <div class="alert alert-warning d-flex align-items-start mb-4">
                        <i class="bi bi-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Restricted Area:</strong> This portal is restricted to authorized administrative personnel only. All access attempts are monitored and logged.
                        </div>
                    </div>
                    
                    <p class="admin-card-text">
                        Administrative portal for PDEX system management with enhanced security and role-based access controls.
                    </p>
                    
                    <a href="/admin/auth" class="btn btn-bc-gov w-100 mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-shaded me-2"></i>
                        Admin Login with IDIR
                    </a>
                    
                    <div class="text-center">
                        <a href="/login" class="back-link">
                            <i class="bi bi-arrow-left me-2"></i>Back to Main Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
