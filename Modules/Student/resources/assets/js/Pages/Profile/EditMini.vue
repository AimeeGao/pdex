<template>
  <AuthenticatedLayout>
    <div class="container-fluid py-4">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow">
            <div class="card-header bg-primary text-light">
              <h4 class="mb-0">
                <i class="bi bi-person-gear me-2"></i>
                Edit Profile - Essential Information
              </h4>
            </div>

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
                <!-- Name Information -->
                <div class="mb-4">
                  <h6 class="border-bottom pb-2 mb-3">
                    <i class="bi bi-person-badge me-2"></i>Name Information
                  </h6>
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                      <input
                        id="first_name"
                        v-model="form.first_name"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.first_name }"
                        required
                      />
                      <div v-if="form.errors.first_name" class="invalid-feedback">
                        {{ form.errors.first_name }}
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="middle_name" class="form-label">Middle Name</label>
                      <input
                        id="middle_name"
                        v-model="form.middle_name"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.middle_name }"
                      />
                      <div v-if="form.errors.middle_name" class="invalid-feedback">
                        {{ form.errors.middle_name }}
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                      <input
                        id="last_name"
                        v-model="form.last_name"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.last_name }"
                        required
                      />
                      <div v-if="form.errors.last_name" class="invalid-feedback">
                        {{ form.errors.last_name }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Personal Details -->
                <div class="mb-4">
                  <h6 class="border-bottom pb-2 mb-3">
                    <i class="bi bi-calendar me-2"></i>Personal Details
                  </h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                      <input
                        id="date_of_birth"
                        v-model="form.date_of_birth"
                        type="date"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.date_of_birth }"
                        required
                      />
                      <div v-if="form.errors.date_of_birth" class="invalid-feedback">
                        {{ form.errors.date_of_birth }}
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="gender" class="form-label">Gender</label>
                      <select
                        id="gender"
                        v-model="form.gender"
                        class="form-select"
                        :class="{ 'is-invalid': form.errors.gender }"
                      >
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="non-binary">Non-binary</option>
                        <option value="other">Other</option>
                        <option value="prefer_not_to_say">Prefer not to say</option>
                      </select>
                      <div v-if="form.errors.gender" class="invalid-feedback">
                        {{ form.errors.gender }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-4">
                  <h6 class="border-bottom pb-2 mb-3">
                    <i class="bi bi-envelope me-2"></i>Contact Information
                  </h6>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                      <input
                        id="email"
                        v-model="form.email_address"
                        type="email"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.email_address }"
                        required
                      />
                      <div v-if="form.errors.email_address" class="invalid-feedback">
                        {{ form.errors.email_address }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Identity Numbers -->
                <div class="mb-4">
                  <h6 class="border-bottom pb-2 mb-3">
                    <i class="bi bi-card-text me-2"></i>Identity Numbers
                  </h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="sin" class="form-label">Social Insurance Number (SIN)</label>
                      <div class="input-group">
                        <input
                          id="sin"
                          v-model="form.social_insurance_number"
                          :type="showSIN ? 'text' : 'password'"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.social_insurance_number }"
                          placeholder="000-000-000"
                          maxlength="11"
                          @input="formatSIN"
                        />
                        <button 
                          class="btn btn-outline-secondary" 
                          type="button" 
                          @click="showSIN = !showSIN"
                          :aria-label="showSIN ? 'Hide SIN' : 'Show SIN'"
                        >
                          <i :class="showSIN ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </button>
                      </div>
                      <div v-if="form.errors.social_insurance_number" class="invalid-feedback d-block">
                        {{ form.errors.social_insurance_number }}
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="pen_number" class="form-label">Provincial Education Number (PEN)</label>
                      <input
                        id="pen_number"
                        v-model="form.provincial_education_number"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.provincial_education_number }"
                        placeholder="e.g., BC Student Number"
                      />
                      <div v-if="form.errors.provincial_education_number" class="invalid-feedback">
                        {{ form.errors.provincial_education_number }}
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Racial Identity -->
                <div class="mb-4">
                  <h6 class="border-bottom pb-2 mb-3">
                    <i class="bi bi-people me-2"></i>Racial Identity
                  </h6>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label class="form-label">Select all that apply</label>
                      <div class="row">
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="BLACK" v-model="form.racial_identity" id="racial_black">
                            <label class="form-check-label" for="racial_black">
                              Black
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="EAST_ASIAN" v-model="form.racial_identity" id="racial_east_asian">
                            <label class="form-check-label" for="racial_east_asian">
                              East Asian
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="INDIGENOUS" v-model="form.racial_identity" id="racial_indigenous">
                            <label class="form-check-label" for="racial_indigenous">
                              Indigenous
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="LATIN_AMERICAN" v-model="form.racial_identity" id="racial_latin_american">
                            <label class="form-check-label" for="racial_latin_american">
                              Latin American
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="MIDDLE_EASTERN" v-model="form.racial_identity" id="racial_middle_eastern">
                            <label class="form-check-label" for="racial_middle_eastern">
                              Middle Eastern
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="SOUTH_ASIAN" v-model="form.racial_identity" id="racial_south_asian">
                            <label class="form-check-label" for="racial_south_asian">
                              South Asian
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="SOUTHEAST_ASIAN" v-model="form.racial_identity" id="racial_southeast_asian">
                            <label class="form-check-label" for="racial_southeast_asian">
                              Southeast Asian
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="WHITE" v-model="form.racial_identity" id="racial_white">
                            <label class="form-check-label" for="racial_white">
                              White
                            </label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ANOTHER_RACIAL_IDENTITY" v-model="form.racial_identity" id="racial_another" @change="handleAnotherRacialIdentityChange">
                            <label class="form-check-label" for="racial_another">
                              Another Racial Identity
                            </label>
                          </div>
                          <div v-if="form.racial_identity.includes('ANOTHER_RACIAL_IDENTITY')" class="mt-2 ms-4">
                            <input
                              v-model="form.racial_identity_other_text"
                              type="text"
                              class="form-control form-control-sm"
                              placeholder="Please specify (200 characters max)"
                              maxlength="200"
                            />
                          </div>
                        </div>
                        <div class="col-12 mt-3 border-top pt-3">
                          <div class="col-md-6 mb-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="DO_NOT_KNOW" v-model="form.racial_identity" id="racial_do_not_know">
                              <label class="form-check-label" for="racial_do_not_know">
                                I do not know / I am not sure
                              </label>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="PREFER_NOT_TO_ANSWER" v-model="form.racial_identity" id="racial_prefer_not">
                              <label class="form-check-label" for="racial_prefer_not">
                                Prefer not to answer
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div v-if="form.errors.racial_identity" class="invalid-feedback d-block">
                        {{ form.errors.racial_identity }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                  <Link :href="backUrl" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                  </Link>
                  <button type="submit" class="btn btn-primary" :disabled="form.processing">
                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                    <i v-else class="bi bi-check-lg me-2"></i>
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
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
import { ref, watch } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '../../Layouts/Authenticated.vue'

const props = defineProps({
  individual: {
    type: Object,
    required: true
  },
  identity: {
    type: Object,
    default: () => ({})
  },
  backUrl: {
    type: String,
    default: '/student'
  }
})

// Helper function to format date for input[type="date"]
const formatDateForInput = (dateString) => {
  if (!dateString) return ''
  // Extract just the date part (YYYY-MM-DD) from ISO timestamp
  return dateString.split('T')[0]
}

// Toggle for showing/hiding SIN
const showSIN = ref(false)

// Initialize form with existing data
const form = useForm({
  first_name: props.individual.first_name || '',
  middle_name: props.individual.middle_name || '',
  last_name: props.individual.last_name || '',
  date_of_birth: formatDateForInput(props.individual.date_of_birth),
  gender: props.individual.gender || '',
  email_address: props.individual.email_address || '',
  social_insurance_number: props.individual.social_insurance_number || '',
  provincial_education_number: props.individual.provincial_education_number || '',
  racial_identity: Array.isArray(props.identity?.racial_identity) 
    ? props.identity.racial_identity 
    : (props.identity?.racial_identity ? [props.identity.racial_identity] : []),
  racial_identity_other_text: props.identity?.racial_identity_other_text || ''
})

// Watch for changes to racial_identity to enforce mutual exclusivity
watch(() => form.racial_identity, (newValue, oldValue) => {
  const exclusiveOptions = ['DO_NOT_KNOW', 'PREFER_NOT_TO_ANSWER']
  
  // Find which option was just added
  const added = newValue.find(val => !oldValue.includes(val))
  
  if (added) {
    if (exclusiveOptions.includes(added)) {
      // If an exclusive option was selected, clear all others
      form.racial_identity = [added]
      form.racial_identity_other_text = ''
    } else {
      // If a regular option was selected, remove any exclusive options
      form.racial_identity = newValue.filter(val => !exclusiveOptions.includes(val))
    }
  }
}, { deep: true })

const handleAnotherRacialIdentityChange = () => {
  if (!form.racial_identity.includes('ANOTHER_RACIAL_IDENTITY')) {
    form.racial_identity_other_text = ''
  }
}

// Format SIN as 000-000-000 (only digits, max 9)
const formatSIN = (event) => {
  let value = event.target.value
  // Remove all non-digit characters
  let digits = value.replace(/\D/g, '')
  // Limit to 9 digits
  digits = digits.substring(0, 9)
  
  // Add dashes: 000-000-000
  if (digits.length > 6) {
    value = digits.substring(0, 3) + '-' + digits.substring(3, 6) + '-' + digits.substring(6)
  } else if (digits.length > 3) {
    value = digits.substring(0, 3) + '-' + digits.substring(3)
  } else {
    value = digits
  }
  
  form.social_insurance_number = value
}

const submitForm = () => {
  // Restructure data to match controller expectations
  const submitData = {
    first_name: form.first_name,
    middle_name: form.middle_name,
    last_name: form.last_name,
    date_of_birth: form.date_of_birth,
    gender: form.gender,
    email_address: form.email_address,
    social_insurance_number: form.social_insurance_number,
    provincial_education_number: form.provincial_education_number,
    identity: {
      racial_identity: form.racial_identity,
      racial_identity_other_text: form.racial_identity_other_text
    }
  }

  form.put('/student/profile', {
    data: submitData,
    onSuccess: () => {
      // Success handled by redirect
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors)
    }
  })
}
</script>

<style scoped>
.card {
  border: none;
  border-radius: 0.5rem;
}

.card-header {
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}

.border-bottom {
  border-bottom: 2px solid #dee2e6 !important;
}

h6 i {
  color: #6c757d;
}
</style>