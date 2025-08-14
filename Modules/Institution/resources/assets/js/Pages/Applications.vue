<template>
    <Authenticated>
        <Head title="Applications" />
        <div class="container-fluid px-4 py-4">
            <div class="mb-4">
                <h1 class="h2 fw-bold text-bc-blue mb-0">Applications</h1>
                <p class="text-muted">Manage your institution's applications</p>
            </div>

            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="bi bi-info-circle me-2"></i>Applications Management
                </h6>
                <p class="mb-0">This section will contain application management features for your institution.</p>
            </div>
        </div>
    </Authenticated>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Authenticated from '../Layouts/Authenticated.vue'

export default {
    name: 'InstitutionApplications',
    components: {
        Head,
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
