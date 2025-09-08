<template>
    <Authenticated>
        <Head :title="`Create Site - ${institution.legal_operating_name}`" />
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
                        <li class="breadcrumb-item active" aria-current="page">Create Site</li>
                    </ol>
                </nav>
                <h1 class="h2 fw-bold text-bc-blue mb-0">Create New Site</h1>
                <p class="text-muted">Add a new site for {{ institution.legal_operating_name }}</p>
            </div>

            <form @submit.prevent="submit">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Site Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Site Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Site Name *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.operating_name }"
                                               v-model="form.operating_name" 
                                               maxlength="255" required>
                                        <div class="invalid-feedback" v-if="errors.operating_name">
                                            {{ errors.operating_name }}
                                        </div>
                                        <div class="form-text">This will be the name for this specific site/campus</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Website</label>
                                        <input type="url" class="form-control" 
                                               :class="{ 'is-invalid': errors.website }"
                                               v-model="form.website" 
                                               placeholder="https://example.com">
                                        <div class="invalid-feedback" v-if="errors.website">
                                            {{ errors.website }}
                                        </div>
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
                                        <label class="form-label">Primary Phone *</label>
                                        <input type="tel" class="form-control" 
                                               :class="{ 'is-invalid': errors.primary_phone }"
                                               v-model="form.primary_phone" 
                                               placeholder="(555) 123-4567"
                                               pattern="^(\+?1[-.\s]?)?\(?[0-9]{3}\)?[-.\s]?[0-9]{3}[-.\s]?[0-9]{4}$"
                                               title="Please enter a valid North American phone number (e.g., (555) 123-4567)"
                                               maxlength="20" required>
                                        <div class="invalid-feedback" v-if="errors.primary_phone">
                                            {{ errors.primary_phone }}
                                        </div>
                                        <div class="form-text">Format: (555) 123-4567</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Primary Email *</label>
                                        <input type="email" class="form-control" 
                                               :class="{ 'is-invalid': errors.primary_email }"
                                               v-model="form.primary_email" 
                                               maxlength="255" required>
                                        <div class="invalid-feedback" v-if="errors.primary_email">
                                            {{ errors.primary_email }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contact First Name *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.contact_first_name }"
                                               v-model="form.contact_first_name" 
                                               maxlength="100" required>
                                        <div class="invalid-feedback" v-if="errors.contact_first_name">
                                            {{ errors.contact_first_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contact Last Name *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.contact_last_name }"
                                               v-model="form.contact_last_name" 
                                               maxlength="100" required>
                                        <div class="invalid-feedback" v-if="errors.contact_last_name">
                                            {{ errors.contact_last_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contact Email *</label>
                                        <input type="email" class="form-control" 
                                               :class="{ 'is-invalid': errors.contact_email }"
                                               v-model="form.contact_email" 
                                               maxlength="255" required>
                                        <div class="invalid-feedback" v-if="errors.contact_email">
                                            {{ errors.contact_email }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contact Phone *</label>
                                        <input type="tel" class="form-control" 
                                               :class="{ 'is-invalid': errors.contact_phone }"
                                               v-model="form.contact_phone" 
                                               placeholder="(555) 123-4567"
                                               pattern="^(\+?1[-.\s]?)?\(?[0-9]{3}\)?[-.\s]?[0-9]{3}[-.\s]?[0-9]{4}$"
                                               title="Please enter a valid North American phone number (e.g., (555) 123-4567)"
                                               maxlength="20" required>
                                        <div class="invalid-feedback" v-if="errors.contact_phone">
                                            {{ errors.contact_phone }}
                                        </div>
                                        <div class="form-text">Format: (555) 123-4567</div>
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
                                        <label class="form-label">Address Line 1 *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.address_line_1 }"
                                               v-model="form.address_line_1" 
                                               maxlength="255" required>
                                        <div class="invalid-feedback" v-if="errors.address_line_1">
                                            {{ errors.address_line_1 }}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Address Line 2</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.address_line_2 }"
                                               v-model="form.address_line_2" 
                                               maxlength="255">
                                        <div class="invalid-feedback" v-if="errors.address_line_2">
                                            {{ errors.address_line_2 }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">City *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.city }"
                                               v-model="form.city" 
                                               maxlength="100" required>
                                        <div class="invalid-feedback" v-if="errors.city">
                                            {{ errors.city }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Province/State *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.province_state }"
                                               v-model="form.province_state" 
                                               value="British Columbia"
                                               maxlength="100" required>
                                        <div class="invalid-feedback" v-if="errors.province_state">
                                            {{ errors.province_state }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Postal Code *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.postal_code }"
                                               v-model="form.postal_code" 
                                               placeholder="A1A 1A1"
                                               pattern="^[A-Za-z]\d[A-Za-z][\s\-]?\d[A-Za-z]\d$"
                                               title="Please enter a valid Canadian postal code (e.g., A1A 1A1)"
                                               maxlength="10" required>
                                        <div class="invalid-feedback" v-if="errors.postal_code">
                                            {{ errors.postal_code }}
                                        </div>
                                        <div class="form-text">Format: A1A 1A1</div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Country *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.country }"
                                               v-model="form.country" 
                                               value="Canada"
                                               maxlength="100" required>
                                        <div class="invalid-feedback" v-if="errors.country">
                                            {{ errors.country }}
                                        </div>
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
                                        <label class="form-label">Regulating Body *</label>
                                        <select class="form-select" 
                                                :class="{ 'is-invalid': errors.regulating_body }"
                                                v-model="form.regulating_body" required>
                                            <option value="">Select Regulating Body</option>
                                            <option v-for="body in regulatingBodies" :key="body" :value="body">
                                                {{ body }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback" v-if="errors.regulating_body">
                                            {{ errors.regulating_body }}
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="form.regulating_body === 'Other'">
                                        <label class="form-label">Other Regulating Body</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.other_regulating_body }"
                                               v-model="form.other_regulating_body" 
                                               maxlength="255">
                                        <div class="invalid-feedback" v-if="errors.other_regulating_body">
                                            {{ errors.other_regulating_body }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Economic Region</label>
                                        <select class="form-select" 
                                                :class="{ 'is-invalid': errors.economic_region }"
                                                v-model="form.economic_region">
                                            <option value="">Select Economic Region</option>
                                            <option v-for="region in economicRegions" :key="region" :value="region">
                                                {{ region }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback" v-if="errors.economic_region">
                                            {{ errors.economic_region }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Standing Status</label>
                                        <select class="form-select" 
                                                :class="{ 'is-invalid': errors.standing_status }"
                                                v-model="form.standing_status">
                                            <option value="">Select Standing Status</option>
                                            <option v-for="status in standingStatuses" :key="status" :value="status">
                                                {{ status }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback" v-if="errors.standing_status">
                                            {{ errors.standing_status }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Established Date</label>
                                        <input type="date" class="form-control" format="yyyy-MM-dd"
                                               :class="{ 'is-invalid': errors.established_date }"
                                               v-model="form.established_date">
                                        <div class="invalid-feedback" v-if="errors.established_date">
                                            {{ errors.established_date }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" 
                                                           v-model="form.public" id="public">
                                                    <label class="form-check-label" for="public">
                                                        Public Institution
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" 
                                                           v-model="form.info_sharing_agreement" id="info_sharing_agreement">
                                                    <label class="form-check-label" for="info_sharing_agreement">
                                                        Info Sharing Agreement
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" 
                                                           v-model="form.active_status" id="active_status">
                                                    <label class="form-check-label" for="active_status">
                                                        Active Site
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Operational Notes</label>
                                        <textarea class="form-control" 
                                                  :class="{ 'is-invalid': errors.notes }"
                                                  v-model="form.notes" 
                                                  rows="3"></textarea>
                                        <div class="invalid-feedback" v-if="errors.notes">
                                            {{ errors.notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Sidebar -->
                    <div class="col-lg-4">
                        
                        <!-- Institution Info -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Institution</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-2">{{ institution.legal_operating_name }}</h6>
                                <p class="small text-muted mb-1">
                                    <strong>Type:</strong> {{ institution.institution_type }}
                                </p>
                                <p class="small text-muted mb-1" v-if="institution.dli">
                                    <strong>DLI:</strong> {{ institution.dli }}
                                </p>
                                <p class="small text-muted mb-0">
                                    <strong>Status: </strong> 
                                    <span :class="institution.active_status ? 'text-success' : 'text-danger'">
                                        {{ institution.active_status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="card sticky-top mt-4" style="top: 80px;">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" :disabled="processing">
                                        <span v-if="processing" class="spinner-border spinner-border-sm me-2"></span>
                                        <i v-else class="bi bi-plus-lg me-2"></i>
                                        {{ processing ? 'Creating...' : 'Create Site' }}
                                    </button>
                                    <Link :href="`/admin/institutions/${institution.id}`" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Cancel
                                    </Link>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </Authenticated>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Authenticated from '../../Layouts/Authenticated.vue'

export default {
    name: 'InstitutionSitesCreate',
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
        economicRegions: {
            type: Array,
            default: () => []
        },
        regulatingBodies: {
            type: Array,
            default: () => []
        },
        standingStatuses: {
            type: Array,
            default: () => []
        },
        errors: {
            type: Object,
            default: () => ({})
        }
    },
    setup(props) {
        const form = useForm({
            operating_name: '',
            primary_phone: '',
            primary_email: '',
            website: '',
            regulating_body: '',
            other_regulating_body: '',
            established_date: '',
            info_sharing_agreement: true,
            contact_first_name: '',
            contact_last_name: '',
            contact_email: '',
            contact_phone: '',
            address_line_1: '',
            address_line_2: '',
            city: '',
            province_state: 'British Columbia',
            country: 'Canada',
            postal_code: '',
            public: true,
            active_status: true,
            standing_status: '',
            economic_region: '',
            notes: ''
        })

        return { form }
    },
    computed: {
        processing() {
            return this.form.processing
        }
    },
    methods: {
        submit() {
            this.form.post(`/admin/institutions/${this.institution.id}/sites`, {
                preserveScroll: true
            })
        }
    }
}
</script>

<style scoped>
.text-bc-blue {
    color: #003366 !important;
}
</style>
