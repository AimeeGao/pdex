<template>
    <Authenticated>
        <Head title="Admin" />
        <div class="container-fluid px-4 py-4">
            <div class="mb-4">
                <h1 class="h2 fw-bold text-bc-blue mb-0">Administration</h1>
                <p class="text-muted">Administrative functions for your institution</p>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <Link href="/institution/settings" class="btn btn-primary">
                                    <i class="bi bi-gear me-2"></i>Institution Settings
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>Administrative Tools
                        </h6>
                        <p class="mb-0">Additional administrative features will be available here as they are developed.</p>
                    </div>
                </div>
            </div>
        </div>
    </Authenticated>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Authenticated from '../Layouts/Authenticated.vue'

export default {
    name: 'InstitutionAdmin',
    components: {
        Head,
        Link,
        Authenticated
    },
    props: {
        user: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped>
.text-bc-blue {
    color: #003366 !important;
}
</style>
