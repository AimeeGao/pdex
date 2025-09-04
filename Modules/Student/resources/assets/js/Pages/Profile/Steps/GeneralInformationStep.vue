<template>
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">
        <i class="bi bi-person me-2"></i>
        General Information
      </h5>
      <p class="text-muted mb-0 small">Personal details and contact information</p>
    </div>
    <div class="card-body">
      
      <!-- Name Information -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-person-badge me-2"></i>Name Information
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
            <input
              id="first_name"
              v-model="form.first_name"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('first_name') }"
              required
            />
            <div v-if="hasFieldError('first_name')" class="invalid-feedback">
              {{ getFieldError('first_name') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input
              id="middle_name"
              v-model="form.middle_name"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('middle_name') }"
            />
            <div v-if="hasFieldError('middle_name')" class="invalid-feedback">
              {{ getFieldError('middle_name') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
            <input
              id="last_name"
              v-model="form.last_name"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('last_name') }"
              required
            />
            <div v-if="hasFieldError('last_name')" class="invalid-feedback">
              {{ getFieldError('last_name') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="preferred_name" class="form-label">Preferred Name</label>
            <input
              id="preferred_name"
              v-model="form.preferred_name"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('preferred_name') }"
            />
            <div v-if="hasFieldError('preferred_name')" class="invalid-feedback">
              {{ getFieldError('preferred_name') }}
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
          <div class="col-md-4 mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input
              id="date_of_birth"
              v-model="form.date_of_birth"
              type="date"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('date_of_birth') }"
            />
            <div v-if="hasFieldError('date_of_birth')" class="invalid-feedback">
              {{ getFieldError('date_of_birth') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select
              id="gender"
              v-model="form.gender"
              class="form-select"
              :class="{ 'is-invalid': hasFieldError('gender') }"
            >
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="non-binary">Non-binary</option>
              <option value="other">Other</option>
              <option value="prefer_not_to_say">Prefer not to say</option>
            </select>
            <div v-if="hasFieldError('gender')" class="invalid-feedback">
              {{ getFieldError('gender') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="preferred_pronouns" class="form-label">Preferred Pronouns</label>
            <input
              id="preferred_pronouns"
              v-model="form.preferred_pronouns"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('preferred_pronouns') }"
              placeholder="e.g., he/him, she/her, they/them"
            />
            <div v-if="hasFieldError('preferred_pronouns')" class="invalid-feedback">
              {{ getFieldError('preferred_pronouns') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-telephone me-2"></i>Contact Information
        </h6>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <input
              id="email"
              v-model="form.email_address"
              type="email"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('email_address') }"
              required
            />
            <div v-if="hasFieldError('email_address')" class="invalid-feedback">
              {{ getFieldError('email_address') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input
              id="phone"
              v-model="form.phone_number"
              type="tel"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('phone_number') }"
              placeholder="(000) 000-0000"
            />
            <div v-if="hasFieldError('phone_number')" class="invalid-feedback">
              {{ getFieldError('phone_number') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="alternate_phone" class="form-label">Alternate Phone Number</label>
            <input
              id="alternate_phone"
              v-model="form.alternate_phone_number"
              type="tel"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('alternate_phone_number') }"
              placeholder="(000) 000-0000"
            />
            <div v-if="hasFieldError('alternate_phone_number')" class="invalid-feedback">
              {{ getFieldError('alternate_phone_number') }}
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
          <div class="col-md-4 mb-3">
            <label for="sin" class="form-label">Social Insurance Number (SIN)</label>
            <input
              id="sin"
              v-model="form.social_insurance_number"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('social_insurance_number') }"
              placeholder="000-000-000"
              maxlength="11"
            />
            <div v-if="hasFieldError('social_insurance_number')" class="invalid-feedback">
              {{ getFieldError('social_insurance_number') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="pen_number" class="form-label">Provincial Education Number (PEN)</label>
            <input
              id="pen_number"
              v-model="form.provincial_education_number"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('provincial_education_number') }"
              placeholder="e.g., BC Student Number"
            />
            <div v-if="hasFieldError('provincial_education_number')" class="invalid-feedback">
              {{ getFieldError('provincial_education_number') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="government_id_number" class="form-label">Government ID Number</label>
            <input
              id="government_id_number"
              v-model="form.government_issued_id"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('government_issued_id') }"
              placeholder="Driver's License, Health Card, etc."
            />
            <div v-if="hasFieldError('government_issued_id')" class="invalid-feedback">
              {{ getFieldError('government_issued_id') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Accessibility -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-universal-access me-2"></i>Accessibility & Accommodation
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="disability_status"
                v-model="form.disability_status"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="disability_status">
                I have a disability or accessibility needs
              </label>
            </div>
          </div>
        </div>
        <div v-if="form.disability_status" class="row">
          <div class="col-md-12 mb-3">
            <label for="accommodation_needs" class="form-label">Accommodation Needs</label>
            <textarea
              id="accommodation_needs"
              v-model="form.accommodation_needs"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('accommodation_needs') }"
              rows="3"
              placeholder="Please describe any accommodations you require"
            ></textarea>
            <div v-if="hasFieldError('accommodation_needs')" class="invalid-feedback">
              {{ getFieldError('accommodation_needs') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watchEffect } from 'vue'

const props = defineProps({
  form: Object,
  errors: Object
})

const emit = defineEmits(['update:form'])

// Helper function to update a field
const updateField = (field, value) => {
  const updatedForm = { ...props.form, [field]: value }
  emit('update:form', updatedForm)
}

// Create a reactive proxy object that syncs with props and emits changes
const form = reactive(new Proxy({}, {
  get(target, property) {
    return props.form?.[property] ?? (typeof props.form?.[property] === 'boolean' ? false : '')
  },
  set(target, property, value) {
    updateField(property, value)
    return true
  },
  has(target, property) {
    return property in (props.form || {})
  },
  ownKeys(target) {
    return Object.keys(props.form || {})
  }
}))

// Watch for prop changes and sync them to our reactive object
watchEffect(() => {
  // This ensures the proxy stays in sync with prop changes
  if (props.form) {
    Object.keys(props.form).forEach(key => {
      // Trigger reactivity by accessing the property
      form[key]
    })
  }
})

// Helper function to check for errors in multiple formats
const getFieldError = (fieldName) => {
  return props.errors[fieldName] || 
         props.errors[`general.${fieldName}`] || 
         null
}

const hasFieldError = (fieldName) => {
  return getFieldError(fieldName) !== null
}
</script>
