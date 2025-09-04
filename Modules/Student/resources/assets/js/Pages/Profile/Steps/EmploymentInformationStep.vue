<template>
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">
        <i class="bi bi-briefcase me-2"></i>
        Employment Information
      </h5>
      <p class="text-muted mb-0 small">Current and previous employment, career goals</p>
    </div>
    <div class="card-body">
      <!-- Current Employment Status -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-person-workspace me-2"></i>Current Employment Status
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="employment_status" class="form-label">Employment Status</label>
            <select
              id="employment_status"
              v-model="form.employment_status"
              class="form-select"
              :class="{ 'is-invalid': hasFieldError('employment_status') }"
            >
              <option value="">Select Status</option>
              <option value="employed">Employed</option>
              <option value="unemployed">Unemployed</option>
              <option value="student">Student</option>
              <option value="self-employed">Self-employed</option>
              <option value="retired">Retired</option>
              <option value="homemaker">Homemaker</option>
              <option value="other">Other</option>
            </select>
            <div v-if="hasFieldError('employment_status')" class="invalid-feedback">
              {{ hasFieldError('employment_status') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-check mt-4">
              <input
                id="is_looking_for_work"
                v-model="form.is_looking_for_work"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="is_looking_for_work">
                Currently looking for work
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Current Employer -->
      <div class="mb-4" v-if="form.employment_status === 'employed' || form.employment_status === 'self-employed'">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-building me-2"></i>Current Employer
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="employer_name" class="form-label">Employer Name</label>
            <input
              id="employer_name"
              v-model="form.employer_name"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('employer_name') }"
            />
            <div v-if="hasFieldError('employer_name')" class="invalid-feedback">
              {{ hasFieldError('employer_name') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="job_title" class="form-label">Job Title</label>
            <input
              id="job_title"
              v-model="form.job_title"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('job_title') }"
            />
            <div v-if="hasFieldError('job_title')" class="invalid-feedback">
              {{ hasFieldError('job_title') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="employer_industry" class="form-label">Industry</label>
            <input
              id="employer_industry"
              v-model="form.employer_industry"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('employer_industry') }"
              placeholder="e.g., Technology, Healthcare, Education"
            />
            <div v-if="hasFieldError('employer_industry')" class="invalid-feedback">
              {{ hasFieldError('employer_industry') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="employment_start_date" class="form-label">Start Date</label>
            <input
              id="employment_start_date"
              v-model="form.employment_start_date"
              type="date"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('employment_start_date') }"
            />
            <div v-if="hasFieldError('employment_start_date')" class="invalid-feedback">
              {{ hasFieldError('employment_start_date') }}
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="employment_end_date" class="form-label">End Date</label>
            <input
              id="employment_end_date"
              v-model="form.employment_end_date"
              type="date"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('employment_end_date') }"
            />
            <div v-if="hasFieldError('employment_end_date')" class="invalid-feedback">
              {{ hasFieldError('employment_end_date') }}
            </div>
            <small class="text-muted">Leave blank if current position</small>
          </div>
          <div class="col-md-3 mb-3">
            <label for="work_hours_per_week" class="form-label">Hours per Week</label>
            <input
              id="work_hours_per_week"
              v-model="form.work_hours_per_week"
              type="number"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('work_hours_per_week') }"
              min="0"
              max="168"
              step="0.5"
            />
            <div v-if="hasFieldError('work_hours_per_week')" class="invalid-feedback">
              {{ hasFieldError('work_hours_per_week') }}
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="monthly_income" class="form-label">Monthly Income ($)</label>
            <input
              id="monthly_income"
              v-model="form.monthly_income"
              type="number"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('monthly_income') }"
              min="0"
              step="100"
              placeholder="0.00"
            />
            <div v-if="hasFieldError('monthly_income')" class="invalid-feedback">
              {{ hasFieldError('monthly_income') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="is_job_related_to_program"
                v-model="form.is_job_related_to_program"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="is_job_related_to_program">
                This job is related to my program of study
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Previous Employment -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-clock-history me-2"></i>Previous Employment
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="previous_job_title" class="form-label">Previous Job Title</label>
            <input
              id="previous_job_title"
              v-model="form.previous_job_title"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('previous_job_title') }"
            />
            <div v-if="hasFieldError('previous_job_title')" class="invalid-feedback">
              {{ hasFieldError('previous_job_title') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="previous_employer_name" class="form-label">Previous Employer Name</label>
            <input
              id="previous_employer_name"
              v-model="form.previous_employer_name"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('previous_employer_name') }"
            />
            <div v-if="hasFieldError('previous_employer_name')" class="invalid-feedback">
              {{ hasFieldError('previous_employer_name') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="previous_employment_start_date" class="form-label">Previous Employment Start Date</label>
            <input
              id="previous_employment_start_date"
              v-model="form.previous_employment_start_date"
              type="date"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('previous_employment_start_date') }"
            />
            <div v-if="hasFieldError('previous_employment_start_date')" class="invalid-feedback">
              {{ hasFieldError('previous_employment_start_date') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="previous_employment_end_date" class="form-label">Previous Employment End Date</label>
            <input
              id="previous_employment_end_date"
              v-model="form.previous_employment_end_date"
              type="date"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('previous_employment_end_date') }"
            />
            <div v-if="hasFieldError('previous_employment_end_date')" class="invalid-feedback">
              {{ hasFieldError('previous_employment_end_date') }}
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="reason_for_leaving" class="form-label">Reason for Leaving</label>
            <input
              id="reason_for_leaving"
              v-model="form.reason_for_leaving"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('reason_for_leaving') }"
              placeholder="e.g., Career advancement, relocation"
            />
            <div v-if="hasFieldError('reason_for_leaving')" class="invalid-feedback">
              {{ hasFieldError('reason_for_leaving') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Employment Support & Benefits -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-shield-check me-2"></i>Employment Support & Benefits
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="is_receiving_employment_insurance"
                v-model="form.is_receiving_employment_insurance"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="is_receiving_employment_insurance">
                Currently receiving Employment Insurance (EI)
              </label>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="is_participating_in_work_study_program"
                v-model="form.is_participating_in_work_study_program"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="is_participating_in_work_study_program">
                Participating in work-study program
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="barriers_to_employment" class="form-label">Barriers to Employment</label>
            <textarea
              id="barriers_to_employment"
              v-model="form.barriers_to_employment"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('barriers_to_employment') }"
              rows="3"
              placeholder="Describe any barriers you face in finding or maintaining employment"
            ></textarea>
            <div v-if="hasFieldError('barriers_to_employment')" class="invalid-feedback">
              {{ hasFieldError('barriers_to_employment') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Career Goals -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-bullseye me-2"></i>Career Goals
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="career_interest_area" class="form-label">Career Interest Area</label>
            <input
              id="career_interest_area"
              v-model="form.career_interest_area"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('career_interest_area') }"
              placeholder="e.g., Information Technology, Business"
            />
            <div v-if="hasFieldError('career_interest_area')" class="invalid-feedback">
              {{ hasFieldError('career_interest_area') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="desired_job_title" class="form-label">Desired Job Title</label>
            <input
              id="desired_job_title"
              v-model="form.desired_job_title"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('desired_job_title') }"
              placeholder="Your career goal position"
            />
            <div v-if="hasFieldError('desired_job_title')" class="invalid-feedback">
              {{ hasFieldError('desired_job_title') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="career_readiness_level" class="form-label">Career Readiness Level</label>
            <select
              id="career_readiness_level"
              v-model="form.career_readiness_level"
              class="form-select"
              :class="{ 'is-invalid': hasFieldError('career_readiness_level') }"
            >
              <option value="">Select Level</option>
              <option value="beginner">Beginner</option>
              <option value="developing">Developing</option>
              <option value="proficient">Proficient</option>
              <option value="advanced">Advanced</option>
            </select>
            <div v-if="hasFieldError('career_readiness_level')" class="invalid-feedback">
              {{ hasFieldError('career_readiness_level') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-check mt-4">
              <input
                id="has_career_plan"
                v-model="form.has_career_plan"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="has_career_plan">
                I have a clear career plan
              </label>
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
         props.errors[`current_employment.${fieldName}`] || 
         props.errors[`employment.${fieldName}`] ||
         null
}

const hasFieldError = (fieldName) => {
  return getFieldError(fieldName) !== null
}
</script>
