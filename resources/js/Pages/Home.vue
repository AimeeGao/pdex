<script>
import { Head } from '@inertiajs/vue3';
import { defineComponent, computed, useAttrs } from "vue";
import { Button } from '@/Components/BCDesign/Button';
import { Header } from '@/Components/BCDesign/Header';
import { Footer } from '@/Components/BCDesign/Footer';

export default defineComponent({
    name: 'Home',
    components: {
        Head,
        Button,
        Header,
        Footer
    },
    props: {
        loginAttempt: Boolean,
        hasAccess: Boolean,
        status: String,
    },
    setup() {
        // Access roles from $attrs
        const userRoles = useAttrs().auth?.roles || [];

        // Define computed property
        const isSuper = computed(() => {
            return userRoles.some(role => role.name === 'Super Admin');
        });

        return {
            isSuper,
            userRoles
        };
    }
});
</script>
<style scoped>
.bg-bc-gov{
    background: none;
    background-color: #036;
    font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
    color: #036;
}
.box-disabled{
    text-decoration: none;
    background-color: #e0e0e0;
    opacity: 0.7;
    color: #888;
    cursor: not-allowed;
}
</style>
<template>
    <Head title="Select Application" />
    
    <div class="min-vh-100" style="background-color: #f8f9fa;">
        <Header />
        
        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bold text-primary mb-3">
                            Provincial Data Exchange (PDEX)
                        </h1>
                        <p class="h5 text-muted">
                            Welcome to the BC Government's Provincial Data Exchange platform
                        </p>
                    </div>

                    <div class="row g-4">
                        <!-- Student Portal -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h2 class="card-title h5 text-primary mb-3">Student Portal</h2>
                                    <p class="card-text text-muted mb-3">
                                        Access your educational records and manage your student information.
                                    </p>
                                    <div class="d-grid gap-2">
                                        <Button 
                                            variant="primary" 
                                            :disabled="!hasAccess"
                                            @click="$inertia.visit('/student/dashboard')"
                                            class="w-100"
                                        >
                                            Student Dashboard
                                        </Button>
                                        <Button 
                                            variant="secondary" 
                                            @click="$inertia.visit('/login?type=student')"
                                            class="w-100"
                                        >
                                            Student Login
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Institution Portal -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h2 class="card-title h5 text-primary mb-3">Institution Portal</h2>
                                    <p class="card-text text-muted mb-3">
                                        Manage institutional data and access educational analytics.
                                    </p>
                                    <div class="d-grid gap-2">
                                        <Button 
                                            variant="primary" 
                                            :disabled="!hasAccess"
                                            @click="$inertia.visit('/institution/dashboard')"
                                            class="w-100"
                                        >
                                            Institution Dashboard
                                        </Button>
                                        <Button 
                                            variant="secondary" 
                                            @click="$inertia.visit('/login?type=institution')"
                                            class="w-100"
                                        >
                                            Institution Login
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ministry Portal -->
                        <div v-if="isSuper" class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h2 class="card-title h5 text-primary mb-3">Ministry Portal</h2>
                                    <p class="card-text text-muted mb-3">
                                        Ministry-level administration and system oversight.
                                    </p>
                                    <div class="d-grid gap-2">
                                        <Button 
                                            variant="primary" 
                                            @click="$inertia.visit('/admin/home')"
                                            class="w-100"
                                        >
                                            Admin Dashboard
                                        </Button>
                                        <Button 
                                            variant="secondary" 
                                            @click="$inertia.visit('/login?type=ministry')"
                                            class="w-100"
                                        >
                                            Ministry Login
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div v-if="status" class="mt-4 text-center">
                        <div class="alert alert-success d-inline-block">
                            {{ status }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <Footer />
    </div>
</template>
