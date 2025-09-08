<template>
    <Authenticated>
        <Head :title="`Edit ${site.operating_name || 'Site'} - ${institution.legal_operating_name}`" />
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
                            <Link :href="`/admin/institutions/${institution.id}/sites/${site.id}`">{{ site.operating_name || 'Site Details' }}</Link>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold text-bc-blue mb-0">Edit Site</h1>
                        <p class="text-muted">Update site information for {{ institution.legal_operating_name }}</p>
                    </div>
                    <div>
                        <Link class="btn btn-outline-secondary" :href="`/admin/institutions/${institution.id}/sites/${site.id}`">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </Link>
                    </div>
                </div>
            </div>

            <form @submit.prevent="updateSite" enctype="multipart/form-data">
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
                                        <label for="operating_name" class="form-label">Site Name *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.operating_name }"
                                            id="operating_name" 
                                            v-model="form.operating_name" 
                                            placeholder="e.g., Main Campus, Downtown Location"
                                            required
                                        >
                                        <div v-if="form.errors.operating_name" class="invalid-feedback">
                                            {{ form.errors.operating_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="website" class="form-label">Website</label>
                                        <input 
                                            type="url" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.website }"
                                            id="website" 
                                            v-model="form.website" 
                                            placeholder="https://www.example.com"
                                        >
                                        <div v-if="form.errors.website" class="invalid-feedback">
                                            {{ form.errors.website }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="active_status" class="form-label">Status *</label>
                                        <select 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.active_status }"
                                            id="active_status" 
                                            v-model="form.active_status"
                                            required
                                        >
                                            <option value="" disabled>Select Status</option>
                                            <option :value="true">Active</option>
                                            <option :value="false">Inactive</option>
                                        </select>
                                        <div v-if="form.errors.active_status" class="invalid-feedback">
                                            {{ form.errors.active_status }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="public" class="form-label">Institution Type</label>
                                        <select 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.public }"
                                            id="public" 
                                            v-model="form.public"
                                        >
                                            <option value="" disabled>Select Type</option>
                                            <option :value="true">Public</option>
                                            <option :value="false">Private</option>
                                        </select>
                                        <div v-if="form.errors.public" class="invalid-feedback">
                                            {{ form.errors.public }}
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
                                        <label for="contact_first_name" class="form-label">Contact First Name *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.contact_first_name }"
                                            id="contact_first_name" 
                                            v-model="form.contact_first_name" 
                                            required
                                        >
                                        <div v-if="form.errors.contact_first_name" class="invalid-feedback">
                                            {{ form.errors.contact_first_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contact_last_name" class="form-label">Contact Last Name *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.contact_last_name }"
                                            id="contact_last_name" 
                                            v-model="form.contact_last_name" 
                                            required
                                        >
                                        <div v-if="form.errors.contact_last_name" class="invalid-feedback">
                                            {{ form.errors.contact_last_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contact_email" class="form-label">Contact Email *</label>
                                        <input 
                                            type="email" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.contact_email }"
                                            id="contact_email" 
                                            v-model="form.contact_email" 
                                            required
                                        >
                                        <div v-if="form.errors.contact_email" class="invalid-feedback">
                                            {{ form.errors.contact_email }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contact_phone" class="form-label">Contact Phone *</label>
                                        <input 
                                            type="tel" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.contact_phone }"
                                            id="contact_phone" 
                                            v-model="form.contact_phone" 
                                            placeholder="(555) 123-4567"
                                            pattern="^(\+?1[-.\s]?)?\(?[0-9]{3}\)?[-.\s]?[0-9]{3}[-.\s]?[0-9]{4}$"
                                            title="Please enter a valid North American phone number (e.g., (555) 123-4567)"
                                            required
                                        >
                                        <div v-if="form.errors.contact_phone" class="invalid-feedback">
                                            {{ form.errors.contact_phone }}
                                        </div>
                                        <div class="form-text">Format: (555) 123-4567</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="primary_phone" class="form-label">Primary Phone</label>
                                        <input 
                                            type="tel" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.primary_phone }"
                                            id="primary_phone" 
                                            v-model="form.primary_phone"
                                            placeholder="(555) 123-4567"
                                            pattern="^(\+?1[-.\s]?)?\(?[0-9]{3}\)?[-.\s]?[0-9]{3}[-.\s]?[0-9]{4}$"
                                            title="Please enter a valid North American phone number (e.g., (555) 123-4567)"
                                        >
                                        <div v-if="form.errors.primary_phone" class="invalid-feedback">
                                            {{ form.errors.primary_phone }}
                                        </div>
                                        <div class="form-text">Format: (555) 123-4567</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="primary_email" class="form-label">Primary Email</label>
                                        <input 
                                            type="email" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.primary_email }"
                                            id="primary_email" 
                                            v-model="form.primary_email"
                                        >
                                        <div v-if="form.errors.primary_email" class="invalid-feedback">
                                            {{ form.errors.primary_email }}
                                        </div>
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
                                        <label for="address_line_1" class="form-label">Address Line 1 *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.address_line_1 }"
                                            id="address_line_1" 
                                            v-model="form.address_line_1" 
                                            required
                                        >
                                        <div v-if="form.errors.address_line_1" class="invalid-feedback">
                                            {{ form.errors.address_line_1 }}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="address_line_2" class="form-label">Address Line 2</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.address_line_2 }"
                                            id="address_line_2" 
                                            v-model="form.address_line_2"
                                        >
                                        <div v-if="form.errors.address_line_2" class="invalid-feedback">
                                            {{ form.errors.address_line_2 }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">City *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.city }"
                                            id="city" 
                                            v-model="form.city" 
                                            required
                                        >
                                        <div v-if="form.errors.city" class="invalid-feedback">
                                            {{ form.errors.city }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="province_state" class="form-label">Province/State *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.province_state }"
                                            id="province_state" 
                                            v-model="form.province_state" 
                                            required
                                        >
                                        <div v-if="form.errors.province_state" class="invalid-feedback">
                                            {{ form.errors.province_state }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="postal_code" class="form-label">Postal Code *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.postal_code }"
                                            id="postal_code" 
                                            v-model="form.postal_code" 
                                            placeholder="A1A 1A1"
                                            pattern="^[A-Za-z]\d[A-Za-z][\s\-]?\d[A-Za-z]\d$"
                                            title="Please enter a valid Canadian postal code (e.g., A1A 1A1)"
                                            required
                                        >
                                        <div v-if="form.errors.postal_code" class="invalid-feedback">
                                            {{ form.errors.postal_code }}
                                        </div>
                                        <div class="form-text">Format: A1A 1A1</div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="country" class="form-label">Country *</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.country }"
                                            id="country" 
                                            v-model="form.country" 
                                            required
                                        >
                                        <div v-if="form.errors.country" class="invalid-feedback">
                                            {{ form.errors.country }}
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
                                        <label for="regulating_body" class="form-label">Regulating Body *</label>
                                        <select 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.regulating_body }"
                                            id="regulating_body" 
                                            v-model="form.regulating_body"
                                            required
                                        >
                                            <option value="" disabled>Select Regulating Body</option>
                                            <option value="PTIB">PTIB</option>
                                            <option value="CTC">CTC</option>
                                            <option value="CAPFE">CAPFE</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div v-if="form.errors.regulating_body" class="invalid-feedback">
                                            {{ form.errors.regulating_body }}
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="form.regulating_body === 'Other'">
                                        <label for="other_regulating_body" class="form-label">Other Regulating Body</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.other_regulating_body }"
                                            id="other_regulating_body" 
                                            v-model="form.other_regulating_body"
                                        >
                                        <div v-if="form.errors.other_regulating_body" class="invalid-feedback">
                                            {{ form.errors.other_regulating_body }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="economic_region" class="form-label">Economic Region</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.economic_region }"
                                            id="economic_region" 
                                            v-model="form.economic_region"
                                        >
                                        <div v-if="form.errors.economic_region" class="invalid-feedback">
                                            {{ form.errors.economic_region }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="standing_status" class="form-label">Standing Status</label>
                                        <select 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.standing_status }"
                                            id="standing_status" 
                                            v-model="form.standing_status"
                                        >
                                            <option value="" disabled>Select Standing Status</option>
                                            <option value="Good Standing">Good Standing</option>
                                            <option value="Under Review">Under Review</option>
                                            <option value="Probation">Probation</option>
                                            <option value="Suspended">Suspended</option>
                                        </select>
                                        <div v-if="form.errors.standing_status" class="invalid-feedback">
                                            {{ form.errors.standing_status }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="established_date" class="form-label">Established Date</label>
                                        <input 
                                            type="date" format="yyyy-MM-dd"
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.established_date }"
                                            id="established_date" 
                                            v-model="form.established_date"
                                        >
                                        <div v-if="form.errors.established_date" class="invalid-feedback">
                                            {{ form.errors.established_date }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="info_sharing_agreement" class="form-label">Info Sharing Agreement</label>
                                        <select 
                                            class="form-control" 
                                            :class="{ 'is-invalid': form.errors.info_sharing_agreement }"
                                            id="info_sharing_agreement" 
                                            v-model="form.info_sharing_agreement"
                                        >
                                            <option value="" disabled>Select Agreement Status</option>
                                            <option :value="true">Yes</option>
                                            <option :value="false">No</option>
                                        </select>
                                        <div v-if="form.errors.info_sharing_agreement" class="invalid-feedback">
                                            {{ form.errors.info_sharing_agreement }}
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
                                            placeholder="Additional notes or comments about this site..."
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
                        <div class="card sticky-top" style="top: 80px;">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Save Changes</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">
                                    Review your changes before saving. This will update the site information.
                                </p>
                                <div class="d-grid gap-2">
                                    <button 
                                        type="submit" 
                                        class="btn btn-primary" 
                                        :disabled="form.processing"
                                    >
                                        <span v-if="form.processing">
                                            <i class="bi bi-hourglass-split me-2"></i>Saving...
                                        </span>
                                        <span v-else>
                                            <i class="bi bi-check-lg me-2"></i>Update Site
                                        </span>
                                    </button>
                                    <Link 
                                        class="btn btn-outline-secondary" 
                                        :href="`/admin/institutions/${institution.id}/sites/${site.id}`"
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

// Helper function to format date for HTML date input (YYYY-MM-DD)
function formatDateForInput(dateString) {
    if (!dateString) return '';
    
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        
        // Format as YYYY-MM-DD for HTML date input
        return date.toISOString().split('T')[0];
    } catch (error) {
        console.warn('Date formatting error:', error);
        return '';
    }
}

export default {
    name: 'InstitutionSitesEdit',
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
        }
    },
    setup(props) {
        const form = useForm({
            operating_name: props.site.operating_name || '',
            website: props.site.website || '',
            active_status: props.site.active_status,
            public: props.site.public,
            contact_first_name: props.site.contact_first_name || '',
            contact_last_name: props.site.contact_last_name || '',
            contact_email: props.site.contact_email || '',
            contact_phone: props.site.contact_phone || '',
            primary_phone: props.site.primary_phone || '',
            primary_email: props.site.primary_email || '',
            address_line_1: props.site.address_line_1 || '',
            address_line_2: props.site.address_line_2 || '',
            city: props.site.city || '',
            province_state: props.site.province_state || '',
            postal_code: props.site.postal_code || '',
            country: props.site.country || '',
            regulating_body: props.site.regulating_body || '',
            other_regulating_body: props.site.other_regulating_body || '',
            economic_region: props.site.economic_region || '',
            standing_status: props.site.standing_status || '',
            established_date: props.site.established_date ? formatDateForInput(props.site.established_date) : '',
            info_sharing_agreement: props.site.info_sharing_agreement,
            notes: props.site.notes || ''
        });

        const updateSite = () => {
            form.put(`/admin/institutions/${props.institution.id}/sites/${props.site.id}`, {
                onSuccess: () => {
                    // Success handled by controller redirect
                },
                onError: (errors) => {
                    console.log('Update errors:', errors);
                }
            });
        };

        return {
            form,
            updateSite
        };
    }
}
</script>

<style scoped>
.text-bc-blue {
    color: #003366 !important;
}
</style>
