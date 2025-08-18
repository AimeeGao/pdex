<template>
  <AuthenticatedLayout>
    <div class="container-fluid py-4">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card shadow">
            <div class="card-header bg-primary text-light">
              <h4 class="mb-0">
                <i class="bi bi-person-gear me-2"></i>
                Edit Profile
              </h4>
            </div>
            
            <!-- Progress Bar -->
            <div class="card-body py-3 border-bottom">
              <div class="progress mb-3" style="height: 8px;">
                <div 
                  class="progress-bar bg-primary" 
                  role="progressbar" 
                  :style="{ width: progressPercentage + '%' }"
                  :aria-valuenow="progressPercentage" 
                  aria-valuemin="0" 
                  aria-valuemax="100"
                ></div>
              </div>
              
              <div class="row text-center">
                <div class="col-3">
                  <div class="step" :class="{ 'active': currentStep >= 1, 'completed': currentStep > 1 }">
                    <div class="step-icon">
                      <i class="bi bi-person"></i>
                    </div>
                    <div class="step-title">General Info</div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="step" :class="{ 'active': currentStep >= 2, 'completed': currentStep > 2 }">
                    <div class="step-icon">
                      <i class="bi bi-house"></i>
                    </div>
                    <div class="step-title">Address</div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="step" :class="{ 'active': currentStep >= 3, 'completed': currentStep > 3 }">
                    <div class="step-icon">
                      <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="step-title">Employment</div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="step" :class="{ 'active': currentStep >= 4, 'completed': currentStep > 4 }">
                    <div class="step-icon">
                      <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="step-title">Identity</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submitForm">
              <!-- Error Alert -->
              <div v-if="Object.keys(form.errors).length > 0" class="alert alert-danger mx-3 mt-3" role="alert">
                <div class="d-flex align-items-center">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i>
                  <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-1">
                      <li v-for="(error, field) in form.errors" :key="field">
                        {{ error }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <!-- Step 1: General Information -->
                <GeneralInformationStep 
                  v-if="currentStep === 1"
                  :form="form"
                  :errors="form.errors"
                  :countries="countries"
                />

                <!-- Step 2: Address Information -->
                <AddressInformationStep 
                  v-if="currentStep === 2"
                  :current-address="form.current_address"
                  :mailing-address="form.mailing_address"
                  :use-different-mailing="form.use_different_mailing_address"
                  :countries="countries"
                  :errors="form.errors"
                  @update:current-address="form.current_address = $event"
                  @update:mailing-address="form.mailing_address = $event"
                  @update:use-different-mailing="form.use_different_mailing_address = $event"
                />

                <!-- Step 3: Employment Information -->
                <EmploymentInformationStep 
                  v-if="currentStep === 3"
                  :form="form"
                  :errors="form.errors"
                />

                <!-- Step 4: Identity Information -->
                <IdentityInformationStep 
                  v-if="currentStep === 4"
                  :form="form"
                  :errors="form.errors"
                />
              </div>

              <!-- Navigation Buttons -->
              <div class="card-footer bg-light">
                <div class="d-flex justify-content-between">
                  <button 
                    type="button" 
                    class="btn btn-outline-secondary"
                    @click="previousStep"
                    :disabled="currentStep === 1"
                  >
                    <i class="bi bi-arrow-left me-2"></i>
                    Previous
                  </button>

                  <div class="text-muted">
                    Step {{ currentStep }} of 4
                  </div>

                  <button 
                    v-if="currentStep < 4"
                    type="button" 
                    class="btn btn-primary"
                    @click="nextStep"
                    :disabled="!canProceedToNextStep"
                  >
                    Next
                    <i class="bi bi-arrow-right ms-2"></i>
                  </button>

                  <button 
                    v-else
                    type="submit" 
                    class="btn btn-success"
                    :disabled="form.processing || !canSubmitForm"
                  >
                    <i class="bi bi-save me-2"></i>
                    Update Profile
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/Authenticated.vue'
import GeneralInformationStep from './Steps/GeneralInformationStep.vue'
import AddressInformationStep from './Steps/AddressInformationStep.vue'
import EmploymentInformationStep from './Steps/EmploymentInformationStep.vue'
import IdentityInformationStep from './Steps/IdentityInformationStep.vue'

// Props
const props = defineProps({
  individual: {
    type: Object,
    required: true
  },
  countries: {
    type: Array,
    default: () => []
  }
})

// Current step tracking
const currentStep = ref(1)

// Helper functions to get current data
const getCurrentAddress = (type = 'current') => {
  const addresses = props.individual.addresses || []
  // For current address, find the primary address (is_primary = true)
  // For mailing address, find the non-primary address (is_primary = false)
  const isPrimary = type === 'current'
  const address = addresses.find(addr => addr.is_primary === isPrimary)
  return address ? {
    address_line1: address.address_line1 || address.street_address || '',
    address_line2: address.address_line2 || address.apartment_unit || '',
    city: address.city || '',
    province: address.province || address.province_state || '',
    postal_code: address.postal_code || '',
    country: address.country || 'Canada'
  } : {
    address_line1: '',
    address_line2: '',
    city: '',
    province: '',
    postal_code: '',
    country: 'Canada'
  }
}

const getCurrentEmployment = () => {
  const employments = props.individual.employments || []
  const employment = employments.find(emp => emp.is_current) || employments[0] // fallback to first employment if no current found
  return employment ? {
    employment_status: employment.employment_status || '',
    is_looking_for_work: employment.is_looking_for_work || false,
    employer_name: employment.employer_name || '',
    job_title: employment.job_title || '',
    employer_industry: employment.employer_industry || employment.industry || '',
    employment_start_date: employment.employment_start_date || employment.start_date || '',
    employment_end_date: employment.employment_end_date || employment.end_date || '',
    work_hours_per_week: employment.work_hours_per_week || employment.hours_per_week || '',
    monthly_income: employment.monthly_income || employment.salary_range || '',
    is_job_related_to_program: employment.is_job_related_to_program || false,
    previous_job_title: employment.previous_job_title || '',
    previous_employer_name: employment.previous_employer_name || '',
    previous_employment_start_date: employment.previous_employment_start_date || '',
    previous_employment_end_date: employment.previous_employment_end_date || '',
    reason_for_leaving: employment.reason_for_leaving || '',
    is_receiving_employment_insurance: employment.is_receiving_employment_insurance || false,
    is_participating_in_work_study_program: employment.is_participating_in_work_study_program || false,
    barriers_to_employment: employment.barriers_to_employment || '',
    career_interest_area: employment.career_interest_area || '',
    desired_job_title: employment.desired_job_title || '',
    career_readiness_level: employment.career_readiness_level || '',
    has_career_plan: employment.has_career_plan || false
  } : {
    employment_status: '',
    is_looking_for_work: false,
    employer_name: '',
    job_title: '',
    employer_industry: '',
    employment_start_date: '',
    employment_end_date: '',
    work_hours_per_week: '',
    monthly_income: '',
    is_job_related_to_program: false,
    previous_job_title: '',
    previous_employer_name: '',
    previous_employment_start_date: '',
    previous_employment_end_date: '',
    reason_for_leaving: '',
    is_receiving_employment_insurance: false,
    is_participating_in_work_study_program: false,
    barriers_to_employment: '',
    career_interest_area: '',
    desired_job_title: '',
    career_readiness_level: '',
    has_career_plan: false
  }
}

const getCurrentIdentity = () => {
  const identities = props.individual.identities || []
  const identity = identities[0] // just take the first (and likely only) identity record
  return identity ? {
    citizenship_status: identity.citizenship_status || '',
    country_of_birth: identity.country_of_birth || '',
    immigration_status: identity.immigration_status || '',
    years_in_country: identity.years_in_country || '',
    language_spoken_at_home: identity.language_spoken_at_home || '',
    racial_identity: identity.racial_identity || '',
    is_visible_minority: identity.is_visible_minority || false,
    indigenous_status: identity.indigenous_status || identity.indigenous_identity || '',
    indigenous_group: identity.indigenous_group || identity.indigenous_ancestry || '',
    band_affiliation: identity.band_affiliation || identity.first_nation_band || '',
    indigenous_status_card_number: identity.indigenous_status_card_number || '',
    is_registered_with_band: identity.is_registered_with_band || false,
    on_reserve_resident: identity.on_reserve_resident || false,
    receives_indigenous_support_services: identity.receives_indigenous_support_services || false,
    receives_minority_support_services: identity.receives_minority_support_services || false,
    refugee_status: identity.refugee_status || '',
    // Legacy fields for backward compatibility
    is_indigenous: identity.is_indigenous || false,
    indigenous_identity: identity.indigenous_identity || '',
    indigenous_ancestry: identity.indigenous_ancestry || '',
    first_nation_band: identity.first_nation_band || '',
    support_services_needed: identity.support_services_needed || [],
    support_services_other: identity.support_services_other || ''
  } : {
    citizenship_status: '',
    country_of_birth: '',
    immigration_status: '',
    years_in_country: '',
    language_spoken_at_home: '',
    racial_identity: '',
    is_visible_minority: false,
    indigenous_status: '',
    indigenous_group: '',
    band_affiliation: '',
    indigenous_status_card_number: '',
    is_registered_with_band: false,
    on_reserve_resident: false,
    receives_indigenous_support_services: false,
    receives_minority_support_services: false,
    refugee_status: '',
    // Legacy fields for backward compatibility
    is_indigenous: false,
    indigenous_identity: '',
    indigenous_ancestry: '',
    first_nation_band: '',
    support_services_needed: [],
    support_services_other: ''
  }
}

// Form data with existing individual data
const form = useForm({
  // General Information - flat structure to match step components
  social_insurance_number: props.individual.social_insurance_number || '',
  government_issued_id: props.individual.government_issued_id || '',
  first_name: props.individual.first_name || '',
  middle_name: props.individual.middle_name || '',
  last_name: props.individual.last_name || '',
  preferred_name: props.individual.preferred_name || '',
  email_address: props.individual.email_address || '',
  phone_number: props.individual.phone_number || '',
  alternate_phone_number: props.individual.alternate_phone_number || '',
  date_of_birth: props.individual.date_of_birth ? props.individual.date_of_birth.split('T')[0] : '',
  gender: props.individual.gender || '',
  preferred_pronouns: props.individual.preferred_pronouns || '',
  emergency_contact: props.individual.emergency_contact || {
    name: '',
    relationship: '',
    phone: '',
    email: ''
  },
  disability_status: props.individual.disability_status || false,
  accommodation_needs: props.individual.accommodation_needs || '',

  // Address Information
  current_address: getCurrentAddress('current'),
  use_different_mailing_address: false,
  mailing_address: getCurrentAddress('mailing'),

  // Employment Information - spread the employment data to match component expectations
  ...getCurrentEmployment(),

  // Identity Information - spread the identity data to match component expectations
  ...getCurrentIdentity()
})

// Set mailing address flag if mailing address exists
onMounted(() => {
  const mailingAddress = getCurrentAddress('mailing')
  if (mailingAddress.address_line1) {
    form.use_different_mailing_address = true
  }
})

// Computed properties
const progressPercentage = computed(() => {
  return (currentStep.value / 4) * 100
})

const canProceedToNextStep = computed(() => {
  switch (currentStep.value) {
    case 1:
      return form.first_name && 
             form.last_name && 
             form.email_address
    case 2:
      return true // Employment is optional
      // return form.current_address.address_line1 && 
      //        form.current_address.city && 
      //        form.current_address.province && 
      //        form.current_address.postal_code &&
      //        form.current_address.country
    case 3:
      return true // Employment is optional
    case 4:
      return true // Identity is optional
    default:
      return false
  }
})

const canSubmitForm = computed(() => {
  return canProceedToNextStep.value
})

// Methods
const nextStep = () => {
  if (currentStep.value < 4 && canProceedToNextStep.value) {
    currentStep.value++
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const submitForm = () => {
  if (!canSubmitForm.value) return

  // Transform form data for API - form is already flat structure
  const formData = {
    // General information
    social_insurance_number: form.social_insurance_number,
    government_issued_id: form.government_issued_id,
    first_name: form.first_name,
    middle_name: form.middle_name,
    last_name: form.last_name,
    preferred_name: form.preferred_name,
    email_address: form.email_address,
    phone_number: form.phone_number,
    alternate_phone_number: form.alternate_phone_number,
    date_of_birth: form.date_of_birth,
    gender: form.gender,
    preferred_pronouns: form.preferred_pronouns,
    emergency_contact: form.emergency_contact,
    disability_status: form.disability_status,
    accommodation_needs: form.accommodation_needs,

    // Address information
    current_address: form.current_address,
    use_different_mailing_address: form.use_different_mailing_address,
    mailing_address: form.use_different_mailing_address ? form.mailing_address : null,

    // Employment information - collect employment fields with correct key
    current_employment: {
      employment_status: form.employment_status,
      is_looking_for_work: form.is_looking_for_work,
      employer_name: form.employer_name,
      job_title: form.job_title,
      employer_industry: form.employer_industry,
      employment_start_date: form.employment_start_date,
      employment_end_date: form.employment_end_date,
      work_hours_per_week: form.work_hours_per_week,
      monthly_income: form.monthly_income,
      is_job_related_to_program: form.is_job_related_to_program,
      previous_job_title: form.previous_job_title,
      previous_employer_name: form.previous_employer_name,
      previous_employment_start_date: form.previous_employment_start_date,
      previous_employment_end_date: form.previous_employment_end_date,
      reason_for_leaving: form.reason_for_leaving,
      is_receiving_employment_insurance: form.is_receiving_employment_insurance,
      is_participating_in_work_study_program: form.is_participating_in_work_study_program,
      barriers_to_employment: form.barriers_to_employment,
      career_interest_area: form.career_interest_area,
      desired_job_title: form.desired_job_title,
      career_readiness_level: form.career_readiness_level,
      has_career_plan: form.has_career_plan
    },

    // Identity information - collect identity fields
    identity: {
      citizenship_status: form.citizenship_status,
      country_of_birth: form.country_of_birth,
      immigration_status: form.immigration_status,
      years_in_country: form.years_in_country,
      language_spoken_at_home: form.language_spoken_at_home,
      racial_identity: form.racial_identity,
      is_visible_minority: form.is_visible_minority,
      indigenous_status: form.indigenous_status,
      indigenous_group: form.indigenous_group,
      band_affiliation: form.band_affiliation,
      indigenous_status_card_number: form.indigenous_status_card_number,
      is_registered_with_band: form.is_registered_with_band,
      on_reserve_resident: form.on_reserve_resident,
      receives_indigenous_support_services: form.receives_indigenous_support_services,
      receives_minority_support_services: form.receives_minority_support_services,
      refugee_status: form.refugee_status
    }
  }

  form.transform(() => formData)
    .put('/student/profile', {
      onSuccess: () => {
        // Success handled by redirect
      },
      onError: (errors) => {
        console.error('Form submission errors:', errors)
        
        // Navigate to the first step with errors
        if (hasStepErrors(1)) {
          currentStep.value = 1
        } else if (hasStepErrors(2)) {
          currentStep.value = 2
        } else if (hasStepErrors(3)) {
          currentStep.value = 3
        } else if (hasStepErrors(4)) {
          currentStep.value = 4
        }
      }
    })
}

// Helper function to check if a step has errors
const hasStepErrors = (step) => {
  const errorKeys = Object.keys(form.errors)
  
  switch (step) {
    case 1: // General Information
      return errorKeys.some(key => 
        key.includes('social_insurance_number') || 
        key.includes('first_name') || 
        key.includes('last_name') || 
        key.includes('email_address') ||
        key.includes('phone_number') ||
        key.includes('date_of_birth') ||
        key.includes('gender') ||
        key.includes('preferred_pronouns') ||
        key.includes('emergency_contact') ||
        key.includes('disability_status') ||
        key.includes('accommodation_needs')
      )
    case 2: // Address Information
      return errorKeys.some(key => 
        key.includes('address') ||
        key.includes('city') ||
        key.includes('province') ||
        key.includes('postal_code') ||
        key.includes('country') ||
        key.startsWith('current_address.') ||
        key.startsWith('address.')
      )
    case 3: // Employment Information
      return errorKeys.some(key => 
        key.includes('employment') ||
        key.includes('employer') ||
        key.includes('job_title') ||
        key.includes('industry') ||
        key.startsWith('employment.')
      )
    case 4: // Identity Information
      return errorKeys.some(key => 
        key.includes('citizenship') ||
        key.includes('country_of_birth') ||
        key.includes('indigenous') ||
        key.includes('language') ||
        key.includes('support_services') ||
        key.startsWith('identity.')
      )
    default:
      return false
  }
}
</script>

<style scoped>
.step {
  position: relative;
  padding: 10px 0;
}

.step-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #e9ecef;
  color: #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 8px;
  transition: all 0.3s ease;
}

.step-title {
  font-size: 0.875rem;
  color: #6c757d;
  font-weight: 500;
}

.step.active .step-icon {
  background-color: #0d6efd;
  color: #ffffff;
}

.step.active .step-title {
  color: #0d6efd;
  font-weight: 600;
}

.step.completed .step-icon {
  background-color: #198754;
  color: white;
}

.step.completed .step-title {
  color: #198754;
}



@media (max-width: 768px) {
  .step-title {
    font-size: 0.75rem;
  }
  
  .step-icon {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
  }
}
</style>
