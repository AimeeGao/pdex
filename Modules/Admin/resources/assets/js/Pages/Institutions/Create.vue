<template>
    <Authenticated>
        <Head title="Create Institution" />
        <div class="container-fluid px-4 py-4">
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link href="/admin/institutions">Institutions</Link>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create Institution</li>
                    </ol>
                </nav>
                <h1 class="h2 fw-bold text-bc-blue mb-0">Create New Institution</h1>
                <p class="text-muted">Add a new post-secondary institution to the system</p>
            </div>

            <form @submit.prevent="submit">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Institution Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Institution Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Legal Operating Name *</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.legal_operating_name }"
                                               v-model="form.legal_operating_name" 
                                               maxlength="255" required>
                                        <div class="invalid-feedback" v-if="errors.legal_operating_name">
                                            {{ errors.legal_operating_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Institution Type *</label>
                                        <select class="form-select" 
                                                :class="{ 'is-invalid': errors.institution_type }"
                                                v-model="form.institution_type" required>
                                            <option value="">Select Institution Type</option>
                                            <option v-for="type in institutionTypes" :key="type" :value="type">
                                                {{ type }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback" v-if="errors.institution_type">
                                            {{ errors.institution_type }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">DLI Number</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.dli }"
                                               v-model="form.dli" 
                                               placeholder="O12345678"
                                               maxlength="20">
                                        <div class="form-text">Designated Learning Institution number (if applicable)</div>
                                        <div class="invalid-feedback" v-if="errors.dli">
                                            {{ errors.dli }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">BCeID Business GUID</label>
                                        <input type="text" class="form-control" 
                                               :class="{ 'is-invalid': errors.bceid_business_guid }"
                                               v-model="form.bceid_business_guid" 
                                               placeholder="Enter BCeID Business GUID">
                                        <div class="form-text">Business GUID from BCeID system</div>
                                        <div class="invalid-feedback" v-if="errors.bceid_business_guid">
                                            {{ errors.bceid_business_guid }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   v-model="form.active_status" id="active_status">
                                            <label class="form-check-label" for="active_status">
                                                Active Institution
                                            </label>
                                        </div>
                                        <div class="form-text">Inactive institutions cannot receive applications</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps Alert -->
                        <div class="alert alert-success">
                            <h6 class="alert-heading">
                                <i class="bi bi-lightbulb me-2"></i>Next Steps
                            </h6>
                            <p class="mb-0">After creating the institution, you'll be able to add <strong>Institution Sites</strong> with detailed contact information, addresses, and operational data.</p>
                        </div>
                    </div>

                    <!-- Actions Sidebar -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" :disabled="processing">
                                        <span v-if="processing" class="spinner-border spinner-border-sm me-2"></span>
                                        <i v-else class="bi bi-plus-lg me-2"></i>
                                        {{ processing ? 'Creating...' : 'Create Institution' }}
                                    </button>
                                    <Link href="/admin/institutions" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Cancel
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Information -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="small text-muted">
                                    <p><strong>Institution:</strong> Core entity representing the organization.</p>
                                    <p><strong>Institution Sites:</strong> Physical locations with detailed contact and address information.</p>
                                    <p><strong>Institution Relationships:</strong> Connections between institutions (partnerships, consortiums, etc.)</p>
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
    name: 'InstitutionsCreate',
    components: {
        Head,
        Link,
        Authenticated
    },
    props: {
        institutionTypes: {
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
            legal_operating_name: '',
            institution_type: '',
            dli: '',
            bceid_business_guid: '',
            active_status: true
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
            this.form.post('/admin/institutions', {
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
