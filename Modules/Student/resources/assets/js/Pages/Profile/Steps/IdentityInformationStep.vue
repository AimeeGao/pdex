<template>
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">
        <i class="bi bi-person-badge me-2"></i>
        Identity & Cultural Information
      </h5>
      <p class="text-muted mb-0 small">Cultural identity, citizenship, and minority information</p>
    </div>
    <div class="card-body">
      <!-- Citizenship & Immigration -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-flag me-2"></i>Citizenship & Immigration Status
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="citizenship_status" class="form-label">Citizenship Status</label>
            <select
              id="citizenship_status"
              v-model="form.citizenship_status"
              class="form-select"
              :class="{ 'is-invalid': hasFieldError('citizenship_status') }"
            >
              <option value="">Select Status</option>
              <option value="canadian_citizen">Canadian Citizen</option>
              <option value="permanent_resident">Permanent Resident</option>
              <option value="work_permit">Work Permit Holder</option>
              <option value="study_permit">Study Permit Holder</option>
              <option value="visitor">Visitor</option>
              <option value="refugee">Refugee</option>
              <option value="other">Other</option>
            </select>
            <div v-if="hasFieldError('citizenship_status')" class="invalid-feedback">
              {{ hasFieldError('citizenship_status') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="country_of_birth" class="form-label">Country of Birth</label>
            <input
              id="country_of_birth"
              v-model="form.country_of_birth"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('country_of_birth') }"
              placeholder="e.g., Canada, Philippines, India"
            />
            <div v-if="hasFieldError('country_of_birth')" class="invalid-feedback">
              {{ hasFieldError('country_of_birth') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="immigration_status" class="form-label">Immigration Status</label>
            <select
              id="immigration_status"
              v-model="form.immigration_status"
              class="form-select"
              :class="{ 'is-invalid': hasFieldError('immigration_status') }"
            >
              <option value="">Select Status</option>
              <option value="born_in_canada">Born in Canada</option>
              <option value="naturalized_citizen">Naturalized Citizen</option>
              <option value="permanent_resident">Permanent Resident</option>
              <option value="temporary_resident">Temporary Resident</option>
              <option value="refugee_protected_person">Refugee/Protected Person</option>
              <option value="other">Other</option>
            </select>
            <div v-if="hasFieldError('immigration_status')" class="invalid-feedback">
              {{ hasFieldError('immigration_status') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="years_in_country" class="form-label">Years in Canada</label>
            <input
              id="years_in_country"
              v-model="form.years_in_country"
              type="number"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('years_in_country') }"
              min="0"
              max="100"
              placeholder="Years living in Canada"
            />
            <div v-if="hasFieldError('years_in_country')" class="invalid-feedback">
              {{ hasFieldError('years_in_country') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Language & Cultural Background -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-translate me-2"></i>Language & Cultural Background
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="language_spoken_at_home" class="form-label">Language Spoken at Home</label>
            <input
              id="language_spoken_at_home"
              v-model="form.language_spoken_at_home"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('language_spoken_at_home') }"
              placeholder="e.g., English, French, Tagalog, Mandarin"
            />
            <div v-if="hasFieldError('language_spoken_at_home')" class="invalid-feedback">
              {{ hasFieldError('language_spoken_at_home') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Racial & Diversity Information -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-people me-2"></i>Racial & Diversity Information
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="racial_identity" class="form-label">Racial Identity</label>
            <select
              id="racial_identity"
              v-model="form.racial_identity"
              class="form-select"
              :class="{ 'is-invalid': hasFieldError('racial_identity') }"
            >
              <option value="">Select Identity</option>
              <option value="black">Black</option>
              <option value="east_asian">East Asian</option>
              <option value="indigenous">Indigenous</option>
              <option value="latino">Latino</option>
              <option value="middle_eastern">Middle Eastern</option>
              <option value="south_asian">South Asian</option>
              <option value="southeast_asian">Southeast Asian</option>
              <option value="white">White</option>
              <option value="mixed_race">Mixed Race</option>
              <option value="other">Other</option>
              <option value="prefer_not_to_say">Prefer not to say</option>
            </select>
            <div v-if="hasFieldError('racial_identity')" class="invalid-feedback">
              {{ hasFieldError('racial_identity') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-check mt-4">
              <input
                id="is_visible_minority"
                v-model="form.is_visible_minority"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="is_visible_minority">
                I identify as a visible minority
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Indigenous Identity -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-tree me-2"></i>Indigenous Identity
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="is_indigenous"
                v-model="form.indigenous_status"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="is_indigenous">
                I identify as Indigenous
              </label>
            </div>
          </div>
        </div>
        <div v-if="form.indigenous_status" class="row">
          <div class="col-md-6 mb-3">
            <label for="indigenous_group" class="form-label">Indigenous Group</label>
            <select
              id="indigenous_group"
              v-model="form.indigenous_group"
              class="form-select"
              :class="{ 'is-invalid': hasFieldError('indigenous_group') }"
            >
              <option value="">Select Group</option>
              <option value="first_nations">First Nations</option>
              <option value="metis">Métis</option>
              <option value="inuit">Inuit</option>
              <option value="other">Other</option>
            </select>
            <div v-if="hasFieldError('indigenous_group')" class="invalid-feedback">
              {{ hasFieldError('indigenous_group') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="band_affiliation" class="form-label">Band/Nation Affiliation</label>
            <input
              id="band_affiliation"
              v-model="form.band_affiliation"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('band_affiliation') }"
              placeholder="Enter band or nation name"
            />
            <div v-if="hasFieldError('band_affiliation')" class="invalid-feedback">
              {{ hasFieldError('band_affiliation') }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="indigenous_status_card_number" class="form-label">Status Card Number</label>
            <input
              id="indigenous_status_card_number"
              v-model="form.indigenous_status_card_number"
              type="text"
              class="form-control"
              :class="{ 'is-invalid': hasFieldError('indigenous_status_card_number') }"
              placeholder="Status card number (if applicable)"
            />
            <div v-if="hasFieldError('indigenous_status_card_number')" class="invalid-feedback">
              {{ hasFieldError('indigenous_status_card_number') }}
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-check mt-4">
              <input
                id="is_registered_with_band"
                v-model="form.is_registered_with_band"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="is_registered_with_band">
                Registered with band/nation
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="on_reserve_resident"
                v-model="form.on_reserve_resident"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="on_reserve_resident">
                Currently living on reserve
              </label>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="receives_indigenous_support_services"
                v-model="form.receives_indigenous_support_services"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="receives_indigenous_support_services">
                Receiving Indigenous support services
              </label>
            </div>
          </div>
        </div>
        </div>

      <!-- Additional Support Services -->
      <div class="mb-4">
        <h6 class="border-bottom pb-2 mb-3">
          <i class="bi bi-hand-thumbs-up me-2"></i>Support Services
        </h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="form-check">
              <input
                id="receives_minority_support_services"
                v-model="form.receives_minority_support_services"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="receives_minority_support_services">
                Receiving minority support services
              </label>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-check mt-4">
              <input
                id="refugee_status"
                v-model="form.refugee_status"
                class="form-check-input"
                type="checkbox"
              />
              <label class="form-check-label" for="refugee_status">
                I am a refugee or protected person
              </label>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  form: Object,
  errors: Object
})

// Helper function to check for errors in multiple formats
const getFieldError = (fieldName) => {
  return props.errors[fieldName] || 
         props.errors[`identity.${fieldName}`] || 
         null
}

const hasFieldError = (fieldName) => {
  return getFieldError(fieldName) !== null
}
</script>
