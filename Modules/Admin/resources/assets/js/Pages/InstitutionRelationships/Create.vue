<template>
    <Authenticated>
        <Head :title="`Add Relationship - ${institution.legal_operating_name}`" />
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
                        <li class="breadcrumb-item">
                            <Link :href="`/admin/institutions/${institution.id}/relationships`">Relationships</Link>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add New</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold text-bc-blue mb-0">Add New Relationship</h1>
                        <p class="text-muted">Create a new partnership or relationship for {{ institution.legal_operating_name }}</p>
                    </div>
                    <div>
                        <Link class="btn btn-outline-secondary" :href="`/admin/institutions/${institution.id}/relationships`">
                            <i class="bi bi-arrow-left me-2"></i>Back to Relationships
                        </Link>
                    </div>
                </div>
            </div>

            <form @submit.prevent="createRelationship" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Relationship Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Relationship Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="related_institution_guids" class="form-label">Related Institutions *</label>
                                        <div class="form-check mb-2">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                id="selectAll" 
                                                @change="toggleSelectAll"
                                                :checked="allSelected"
                                            >
                                            <label class="form-check-label fw-bold" for="selectAll">
                                                Select All Institutions
                                            </label>
                                        </div>
                                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                            <div 
                                                v-for="inst in availableInstitutions" 
                                                :key="inst.guid" 
                                                class="form-check mb-2"
                                            >
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    :id="`inst_${inst.guid}`"
                                                    :value="inst.guid"
                                                    v-model="form.related_institution_guids"
                                                >
                                                <label class="form-check-label" :for="`inst_${inst.guid}`">
                                                    <div>
                                                        <div class="fw-medium">{{ inst.legal_operating_name }}</div>
                                                        <small class="text-muted">{{ inst.institution_type }}</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div v-if="form.errors.related_institution_guids" class="text-danger mt-2">
                                            {{ form.errors.related_institution_guids }}
                                        </div>
                                        <div class="form-text">
                                            Select one or more institutions to create relationships with. <br>
                                            <strong>Selected: {{ form.related_institution_guids.length }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="relationship_type" class="form-label">Relationship Type *</label>
                                        <select 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.relationship_type }"
                                            id="relationship_type" 
                                            v-model="form.relationship_type"
                                            required
                                        >
                                            <option value="" disabled>Select Type</option>
                                            <option value="geographic">Geographic (Same City/Region)</option>
                                            <option value="academic">Academic Partnership</option>
                                            <option value="partnership">Strategic Partnership</option>
                                            <option value="consortium">Consortium Member</option>
                                            <option value="transfer_agreement">Transfer Agreement</option>
                                            <option value="affiliation">Institutional Affiliation</option>
                                            <option value="federation">Federation Member</option>
                                            <option value="network">Network Member</option>
                                        </select>
                                        <div v-if="form.errors.relationship_type" class="invalid-feedback">
                                            {{ form.errors.relationship_type }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="is_active" class="form-label">Status *</label>
                                        <select 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.is_active }"
                                            id="is_active" 
                                            v-model="form.is_active"
                                            required
                                        >
                                            <option value="" disabled>Select Status</option>
                                            <option :value="true">Active</option>
                                            <option :value="false">Inactive</option>
                                        </select>
                                        <div v-if="form.errors.is_active" class="invalid-feedback">
                                            {{ form.errors.is_active }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="relationship_reason" class="form-label">Relationship Reason</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.relationship_reason }"
                                            id="relationship_reason" 
                                            v-model="form.relationship_reason"
                                            placeholder="e.g., Transfer pathway, Research collaboration"
                                        >
                                        <div v-if="form.errors.relationship_reason" class="invalid-feedback">
                                            {{ form.errors.relationship_reason }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Date Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="effective_date" class="form-label">Effective Date</label>
                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.effective_date }"
                                            id="effective_date" 
                                            v-model="form.effective_date"
                                        >
                                        <div v-if="form.errors.effective_date" class="invalid-feedback">
                                            {{ form.errors.effective_date }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.expiry_date }"
                                            id="expiry_date" 
                                            v-model="form.expiry_date"
                                        >
                                        <div v-if="form.errors.expiry_date" class="invalid-feedback">
                                            {{ form.errors.expiry_date }}
                                        </div>
                                        <div class="form-text">
                                            Leave blank for ongoing relationships.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Additional Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.description }"
                                            id="description" 
                                            v-model="form.description" 
                                            rows="3"
                                            placeholder="Describe the nature and purpose of this relationship..."
                                        ></textarea>
                                        <div v-if="form.errors.description" class="invalid-feedback">
                                            {{ form.errors.description }}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.notes }"
                                            id="notes" 
                                            v-model="form.notes" 
                                            rows="3"
                                            placeholder="Additional notes or comments..."
                                        ></textarea>
                                        <div v-if="form.errors.notes" class="invalid-feedback">
                                            {{ form.errors.notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="col-lg-4">
                        
                        <!-- Institution Info -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Primary Institution</h5>
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
                                    <strong>Status:</strong> 
                                    <span :class="institution.active_status ? 'text-success' : 'text-danger'">
                                        {{ institution.active_status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Selected Institutions Info -->
                        <div v-if="selectedInstitutions.length > 0" class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Selected Institutions ({{ selectedInstitutions.length }})</h5>
                            </div>
                            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                <div v-for="inst in selectedInstitutions" :key="inst.guid" class="border-bottom pb-2 mb-2">
                                    <h6 class="mb-1">{{ inst.legal_operating_name }}</h6>
                                    <p class="small text-muted mb-1">
                                        <strong>Type:</strong> {{ inst.institution_type }}
                                    </p>
                                    <p class="small text-muted mb-1" v-if="inst.dli">
                                        <strong>DLI:</strong> {{ inst.dli }}
                                    </p>
                                    <p class="small text-muted mb-0">
                                        <strong>Status:</strong> 
                                        <span :class="inst.active_status ? 'text-success' : 'text-danger'">
                                            {{ inst.active_status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card sticky-top mt-4" style="top: 80px;">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Create Relationship</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">
                                    Review the relationship details before creating. This will establish a formal relationship between the institutions.
                                </p>
                                <div class="d-grid gap-2">
                                    <button 
                                        type="submit" 
                                        class="btn btn-primary" 
                                        :disabled="form.processing"
                                    >
                                        <span v-if="form.processing">
                                            <i class="bi bi-hourglass-split me-2"></i>Creating...
                                        </span>
                                        <span v-else>
                                            <i class="bi bi-plus-circle me-2"></i>
                                            {{ form.related_institution_guids.length > 1 ? `Create ${form.related_institution_guids.length} Relationships` : 'Create Relationship' }}
                                        </span>
                                    </button>
                                    <Link 
                                        class="btn btn-outline-secondary" 
                                        :href="`/admin/institutions/${institution.id}/relationships`"
                                    >
                                        <i class="bi bi-x-lg me-2"></i>Cancel
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
import { computed } from 'vue'

export default {
    name: 'InstitutionRelationshipsCreate',
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
        availableInstitutions: {
            type: Array,
            default: () => []
        }
    },
    setup(props) {
        const form = useForm({
            related_institution_guids: [],
            relationship_type: '',
            relationship_reason: '',
            is_active: true,
            effective_date: '',
            expiry_date: '',
            description: '',
            notes: ''
        });

        const allSelected = computed(() => {
            return form.related_institution_guids.length === props.availableInstitutions.length;
        });

        const selectedInstitutions = computed(() => {
            if (!form.related_institution_guids.length) return [];
            return props.availableInstitutions.filter(inst => 
                form.related_institution_guids.includes(inst.guid)
            );
        });

        const toggleSelectAll = () => {
            if (allSelected.value) {
                form.related_institution_guids = [];
            } else {
                form.related_institution_guids = props.availableInstitutions.map(inst => inst.guid);
            }
        };

        const createRelationship = () => {
            form.post(`/admin/institutions/${props.institution.id}/relationships`, {
                onSuccess: () => {
                    // Success handled by controller redirect
                },
                onError: (errors) => {
                    console.log('Create errors:', errors);
                }
            });
        };

        return {
            form,
            allSelected,
            selectedInstitutions,
            toggleSelectAll,
            createRelationship
        };
    }
}
</script>

<style scoped>
.text-bc-blue {
    color: #003366 !important;
}
</style>
