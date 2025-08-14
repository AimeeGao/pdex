<template>
    <Authenticated>
        <Head :title="`${site.operating_name || 'Site'} - ${institution.legal_operating_name}`" />
        <div class="container-fluid px-4 py-4">
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link href="/admin/institutions">Institutions</Link>
                        </li>
                        <li class="breadcrumb-item">
                            <Link :href="`/admin/institutions/${institution.id}`">{{ institution.legal_operating_name }}</Link>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ site.operating_name || 'Site Details' }}</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold text-bc-blue mb-0">{{ site.operating_name || 'Main Campus' }}</h1>
                        <p class="text-muted">Site details for {{ institution.legal_operating_name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <Link v-if="canManageInstitutions" class="btn btn-primary" :href="`/admin/institutions/${institution.id}/sites/${site.id}/edit`">
                            <i class="bi bi-pencil me-2"></i>Edit Site
                        </Link>
                        <Link class="btn btn-outline-secondary" :href="`/admin/institutions/${institution.id}`">
                            <i class="bi bi-arrow-left me-2"></i>Back to Institution
                        </Link>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Site Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Site Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Site Name</label>
                                    <p class="mb-0">{{ site.operating_name || 'Main Campus' }}</p>
                                </div>
                                <div class="col-md-6" v-if="site.website">
                                    <label class="form-label text-muted">Website</label>
                                    <p class="mb-0">
                                        <a :href="site.website" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-globe me-1"></i>{{ site.website }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Status</label>
                                    <p class="mb-0">
                                        <span :class="site.active_status ? 'badge bg-success' : 'badge bg-secondary'">
                                            {{ site.active_status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6" v-if="site.public !== null">
                                    <label class="form-label text-muted">Institution Type</label>
                                    <p class="mb-0">
                                        <span :class="site.public ? 'badge bg-primary' : 'badge bg-info'">
                                            {{ site.public ? 'Public' : 'Private' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Primary Contact</label>
                                    <p class="mb-0">{{ site.contact_first_name }} {{ site.contact_last_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Contact Email</label>
                                    <p class="mb-0">
                                        <a :href="`mailto:${site.contact_email}`" class="text-decoration-none">
                                            {{ site.contact_email }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Contact Phone</label>
                                    <p class="mb-0">
                                        <a :href="`tel:${site.contact_phone}`" class="text-decoration-none">
                                            {{ site.contact_phone }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Primary Phone</label>
                                    <p class="mb-0">
                                        <a :href="`tel:${site.primary_phone}`" class="text-decoration-none">
                                            {{ site.primary_phone }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Primary Email</label>
                                    <p class="mb-0">
                                        <a :href="`mailto:${site.primary_email}`" class="text-decoration-none">
                                            {{ site.primary_email }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Address Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label text-muted">Address</label>
                                    <p class="mb-0">
                                        {{ site.address_line_1 }}<br>
                                        <span v-if="site.address_line_2">{{ site.address_line_2 }}<br></span>
                                        {{ site.city }}, {{ site.province_state }} {{ site.postal_code }}<br>
                                        {{ site.country }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operational Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Operational Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Regulating Body</label>
                                    <p class="mb-0">{{ site.regulating_body }}</p>
                                    <p class="mb-0 small text-muted" v-if="site.other_regulating_body">
                                        {{ site.other_regulating_body }}
                                    </p>
                                </div>
                                <div class="col-md-6" v-if="site.economic_region">
                                    <label class="form-label text-muted">Economic Region</label>
                                    <p class="mb-0">{{ site.economic_region }}</p>
                                </div>
                                <div class="col-md-6" v-if="site.standing_status">
                                    <label class="form-label text-muted">Standing Status</label>
                                    <p class="mb-0">
                                        <span :class="site.standing_status === 'Good Standing' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ site.standing_status }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6" v-if="site.established_date">
                                    <label class="form-label text-muted">Established Date</label>
                                    <p class="mb-0">{{ formatDate(site.established_date) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Info Sharing Agreement</label>
                                    <p class="mb-0">
                                        <span :class="site.info_sharing_agreement ? 'badge bg-success' : 'badge bg-secondary'">
                                            {{ site.info_sharing_agreement ? 'Yes' : 'No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-12" v-if="site.notes">
                                    <label class="form-label text-muted">Notes</label>
                                    <p class="mb-0">{{ site.notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">System Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Site GUID</label>
                                <p class="mb-0">
                                    <code class="small">{{ site.guid }}</code>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Institution GUID</label>
                                <p class="mb-0">
                                    <code class="small">{{ site.institution_guid }}</code>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Created</label>
                                <p class="mb-0 small text-muted">{{ formatDate(site.created_at) }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Last Updated</label>
                                <p class="mb-0 small text-muted">{{ formatDate(site.updated_at) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Institution Info -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Parent Institution</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-2">{{ institution.legal_operating_name }}</h6>
                            <p class="small text-muted mb-1">
                                <strong>Type:</strong> {{ institution.institution_type }}
                            </p>
                            <p class="small text-muted mb-1" v-if="institution.dli">
                                <strong>DLI:</strong> {{ institution.dli }}
                            </p>
                            <p class="small text-muted mb-3">
                                <strong>Status:</strong> 
                                <span :class="institution.active_status ? 'text-success' : 'text-danger'">
                                    {{ institution.active_status ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <Link :href="`/admin/institutions/${institution.id}`" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-left me-1"></i>Back to Institution
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Authenticated>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Authenticated from '../../Layouts/Authenticated.vue'

export default {
    name: 'InstitutionSitesShow',
    components: {
        Head,
        Link,
        Authenticated
    },
    props: {
        institution: {
            type: Object,
            required: true
        },
        site: {
            type: Object,
            required: true
        },
        canManageInstitutions: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('en-CA', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
}
</script>

<style scoped>
.text-bc-blue {
    color: #003366 !important;
}
</style>
