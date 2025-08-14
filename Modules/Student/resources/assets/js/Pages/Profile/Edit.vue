<template>
  <AuthenticatedLayout>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit My Profile</h1>
            <Link href="/student/profile" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left me-2"></i>
              Back to Profile
            </Link>
          </div>

          <form @submit.prevent="submit">
            <!-- Sticky Save Button -->
            <div class="bg-white border-bottom p-3 mb-4">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <!-- <button 
                    type="submit" 
                    class="btn btn-primary me-3"
                    :disabled="form.processing"
                  >
                    <i class="bi bi-floppy me-2"></i>
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                  </button>
                  <Link href="/student/profile" class="btn btn-outline-secondary">
                    Cancel
                  </Link> -->
                </div>
                <div class="text-muted small">
                  All fields marked with <span class="text-danger">*</span> are required
                </div>
              </div>
            </div>

            <!-- Form Content -->
            <div class="row">
              <!-- Personal Information -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Personal Information</h5>
                  </div>
                  <div class="card-body">
                    <!-- Identity Numbers -->
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="social_insurance_number" class="form-label">Social Insurance Number <span class="text-danger">*</span></label>
                        <input
                          type="text"
                          id="social_insurance_number"
                          v-model="form.social_insurance_number"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.social_insurance_number }"
                          placeholder="XXX-XXX-XXX"
                          required
                        />
                        <div v-if="form.errors.social_insurance_number" class="invalid-feedback">
                          {{ form.errors.social_insurance_number }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="government_issued_id" class="form-label">Government Issued ID</label>
                        <input
                          type="text"
                          id="government_issued_id"
                          v-model="form.government_issued_id"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.government_issued_id }"
                          placeholder="Driver's License, Passport, etc."
                        />
                        <div v-if="form.errors.government_issued_id" class="invalid-feedback">
                          {{ form.errors.government_issued_id }}
                        </div>
                      </div>
                    </div>
                    
                    <!-- Name Fields -->
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input
                          type="text"
                          id="first_name"
                          v-model="form.first_name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.first_name }"
                          required
                        />
                        <div v-if="form.errors.first_name" class="invalid-feedback">
                          {{ form.errors.first_name }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input
                          type="text"
                          id="last_name"
                          v-model="form.last_name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.last_name }"
                          required
                        />
                        <div v-if="form.errors.last_name" class="invalid-feedback">
                          {{ form.errors.last_name }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input
                          type="text"
                          id="middle_name"
                          v-model="form.middle_name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.middle_name }"
                        />
                        <div v-if="form.errors.middle_name" class="invalid-feedback">
                          {{ form.errors.middle_name }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="preferred_name" class="form-label">Preferred Name</label>
                        <input
                          type="text"
                          id="preferred_name"
                          v-model="form.preferred_name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.preferred_name }"
                        />
                        <div v-if="form.errors.preferred_name" class="invalid-feedback">
                          {{ form.errors.preferred_name }}
                        </div>
                      </div>
                    </div>
                    
                    <!-- Demographics -->
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input
                          type="date"
                          id="date_of_birth"
                          v-model="form.date_of_birth"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.date_of_birth }"
                        />
                        <div v-if="form.errors.date_of_birth" class="invalid-feedback">
                          {{ form.errors.date_of_birth }}
                        </div>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select
                          id="gender"
                          v-model="form.gender"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.gender }"
                        >
                          <option value="">Select Gender</option>
                          <option value="female">Female</option>
                          <option value="male">Male</option>
                          <option value="non-binary">Non-binary</option>
                          <option value="prefer-not-to-say">Prefer not to say</option>
                          <option value="other">Other</option>
                        </select>
                        <div v-if="form.errors.gender" class="invalid-feedback">
                          {{ form.errors.gender }}
                        </div>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="preferred_pronouns" class="form-label">Preferred Pronouns</label>
                        <input
                          type="text"
                          id="preferred_pronouns"
                          v-model="form.preferred_pronouns"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.preferred_pronouns }"
                          placeholder="e.g., she/her, he/him, they/them"
                        />
                        <div v-if="form.errors.preferred_pronouns" class="invalid-feedback">
                          {{ form.errors.preferred_pronouns }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact Information -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Contact Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                      <input
                        type="email"
                        id="email_address"
                        v-model="form.email_address"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.email_address }"
                        required
                      />
                      <div v-if="form.errors.email_address" class="invalid-feedback">
                        {{ form.errors.email_address }}
                      </div>
                      <div v-if="individual.email_verified_at" class="form-text text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Email verified on {{ formatDate(individual.email_verified_at) }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input
                          type="tel"
                          id="phone_number"
                          v-model="form.phone_number"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.phone_number }"
                          placeholder="(123) 456-7890"
                        />
                        <div v-if="form.errors.phone_number" class="invalid-feedback">
                          {{ form.errors.phone_number }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="alternate_phone_number" class="form-label">Alternate Phone</label>
                        <input
                          type="tel"
                          id="alternate_phone_number"
                          v-model="form.alternate_phone_number"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.alternate_phone_number }"
                          placeholder="(123) 456-7890"
                        />
                        <div v-if="form.errors.alternate_phone_number" class="invalid-feedback">
                          {{ form.errors.alternate_phone_number }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Current Address -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Current Address</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label for="current_street_address" class="form-label">Street Address</label>
                      <input
                        type="text"
                        id="current_street_address"
                        v-model="form.current_street_address"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.current_street_address }"
                      />
                      <div v-if="form.errors.current_street_address" class="invalid-feedback">
                        {{ form.errors.current_street_address }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="current_apartment_unit" class="form-label">Apartment/Unit</label>
                        <input
                          type="text"
                          id="current_apartment_unit"
                          v-model="form.current_apartment_unit"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.current_apartment_unit }"
                        />
                        <div v-if="form.errors.current_apartment_unit" class="invalid-feedback">
                          {{ form.errors.current_apartment_unit }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="current_city" class="form-label">City</label>
                        <input
                          type="text"
                          id="current_city"
                          v-model="form.current_city"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.current_city }"
                        />
                        <div v-if="form.errors.current_city" class="invalid-feedback">
                          {{ form.errors.current_city }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="current_province_state" class="form-label">Province/State</label>
                        <input
                          type="text"
                          id="current_province_state"
                          v-model="form.current_province_state"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.current_province_state }"
                        />
                        <div v-if="form.errors.current_province_state" class="invalid-feedback">
                          {{ form.errors.current_province_state }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="current_postal_code" class="form-label">Postal Code</label>
                        <input
                          type="text"
                          id="current_postal_code"
                          v-model="form.current_postal_code"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.current_postal_code }"
                        />
                        <div v-if="form.errors.current_postal_code" class="invalid-feedback">
                          {{ form.errors.current_postal_code }}
                        </div>
                      </div>
                    </div>
                    <CountryAutocomplete
                      id="current_country"
                      label="Country"
                      v-model="form.current_country"
                      :countries="countries"
                      :error="form.errors.current_country"
                      placeholder="Type to search countries..."
                    />
                  </div>
                </div>
              </div>

              <!-- Mailing Address -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Mailing Address</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          id="use_different_mailing_address"
                          v-model="form.use_different_mailing_address"
                          class="form-check-input"
                          :class="{ 'is-invalid': form.errors.use_different_mailing_address }"
                        />
                        <label for="use_different_mailing_address" class="form-check-label">
                          Use different mailing address
                        </label>
                        <div v-if="form.errors.use_different_mailing_address" class="invalid-feedback">
                          {{ form.errors.use_different_mailing_address }}
                        </div>
                      </div>
                    </div>
                    <div v-if="form.use_different_mailing_address">
                      <div class="mb-3">
                        <label for="mailing_street_address" class="form-label">Street Address <span class="text-danger">*</span></label>
                        <input
                          type="text"
                          id="mailing_street_address"
                          v-model="form.mailing_street_address"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.mailing_street_address }"
                        />
                        <div v-if="form.errors.mailing_street_address" class="invalid-feedback">
                          {{ form.errors.mailing_street_address }}
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="mailing_apartment_unit" class="form-label">Apartment/Unit</label>
                          <input
                            type="text"
                            id="mailing_apartment_unit"
                            v-model="form.mailing_apartment_unit"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.mailing_apartment_unit }"
                          />
                          <div v-if="form.errors.mailing_apartment_unit" class="invalid-feedback">
                            {{ form.errors.mailing_apartment_unit }}
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="mailing_city" class="form-label">City <span class="text-danger">*</span></label>
                          <input
                            type="text"
                            id="mailing_city"
                            v-model="form.mailing_city"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.mailing_city }"
                          />
                          <div v-if="form.errors.mailing_city" class="invalid-feedback">
                            {{ form.errors.mailing_city }}
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="mailing_province_state" class="form-label">Province/State <span class="text-danger">*</span></label>
                          <input
                            type="text"
                            id="mailing_province_state"
                            v-model="form.mailing_province_state"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.mailing_province_state }"
                          />
                          <div v-if="form.errors.mailing_province_state" class="invalid-feedback">
                            {{ form.errors.mailing_province_state }}
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="mailing_postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                          <input
                            type="text"
                            id="mailing_postal_code"
                            v-model="form.mailing_postal_code"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.mailing_postal_code }"
                          />
                          <div v-if="form.errors.mailing_postal_code" class="invalid-feedback">
                            {{ form.errors.mailing_postal_code }}
                          </div>
                        </div>
                      </div>
                      <CountryAutocomplete
                        id="mailing_country"
                        label="Country"
                        v-model="form.mailing_country"
                        :countries="countries"
                        :error="form.errors.mailing_country"
                        :required="true"
                        placeholder="Type to search countries..."
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Permanent Address -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Permanent Address</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label for="permanent_street_address" class="form-label">Street Address</label>
                      <input
                        type="text"
                        id="permanent_street_address"
                        v-model="form.permanent_street_address"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.permanent_street_address }"
                      />
                      <div v-if="form.errors.permanent_street_address" class="invalid-feedback">
                        {{ form.errors.permanent_street_address }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="permanent_apartment_unit" class="form-label">Apartment/Unit</label>
                        <input
                          type="text"
                          id="permanent_apartment_unit"
                          v-model="form.permanent_apartment_unit"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.permanent_apartment_unit }"
                        />
                        <div v-if="form.errors.permanent_apartment_unit" class="invalid-feedback">
                          {{ form.errors.permanent_apartment_unit }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="permanent_city" class="form-label">City</label>
                        <input
                          type="text"
                          id="permanent_city"
                          v-model="form.permanent_city"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.permanent_city }"
                        />
                        <div v-if="form.errors.permanent_city" class="invalid-feedback">
                          {{ form.errors.permanent_city }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="permanent_province_state" class="form-label">Province/State</label>
                        <input
                          type="text"
                          id="permanent_province_state"
                          v-model="form.permanent_province_state"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.permanent_province_state }"
                        />
                        <div v-if="form.errors.permanent_province_state" class="invalid-feedback">
                          {{ form.errors.permanent_province_state }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="permanent_postal_code" class="form-label">Postal Code</label>
                        <input
                          type="text"
                          id="permanent_postal_code"
                          v-model="form.permanent_postal_code"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.permanent_postal_code }"
                        />
                        <div v-if="form.errors.permanent_postal_code" class="invalid-feedback">
                          {{ form.errors.permanent_postal_code }}
                        </div>
                      </div>
                    </div>
                    <CountryAutocomplete
                      id="permanent_country"
                      label="Country"
                      v-model="form.permanent_country"
                      :countries="countries"
                      :error="form.errors.permanent_country"
                      placeholder="Type to search countries..."
                    />
                  </div>
                </div>
              </div>

              <!-- Emergency Contact -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Emergency Contact</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label for="emergency_contact_name" class="form-label">Name</label>
                      <input
                        type="text"
                        id="emergency_contact_name"
                        v-model="form.emergency_contact.name"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors['emergency_contact.name'] }"
                      />
                      <div v-if="form.errors['emergency_contact.name']" class="invalid-feedback">
                        {{ form.errors['emergency_contact.name'] }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                        <input
                          type="text"
                          id="emergency_contact_relationship"
                          v-model="form.emergency_contact.relationship"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors['emergency_contact.relationship'] }"
                        />
                        <div v-if="form.errors['emergency_contact.relationship']" class="invalid-feedback">
                          {{ form.errors['emergency_contact.relationship'] }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="emergency_contact_phone" class="form-label">Phone</label>
                        <input
                          type="tel"
                          id="emergency_contact_phone"
                          v-model="form.emergency_contact.phone"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors['emergency_contact.phone'] }"
                        />
                        <div v-if="form.errors['emergency_contact.phone']" class="invalid-feedback">
                          {{ form.errors['emergency_contact.phone'] }}
                        </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="emergency_contact_email" class="form-label">Email</label>
                      <input
                        type="email"
                        id="emergency_contact_email"
                        v-model="form.emergency_contact.email"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors['emergency_contact.email'] }"
                      />
                      <div v-if="form.errors['emergency_contact.email']" class="invalid-feedback">
                        {{ form.errors['emergency_contact.email'] }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Additional Information -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Additional Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="citizenship_status" class="form-label">Citizenship Status</label>
                        <select
                          id="citizenship_status"
                          v-model="form.citizenship_status"
                          class="form-select"
                          :class="{ 'is-invalid': form.errors.citizenship_status }"
                        >
                          <option value="">Select Status</option>
                          <option value="canadian-citizen">Canadian Citizen</option>
                          <option value="permanent-resident">Permanent Resident</option>
                          <option value="study-permit">Study Permit</option>
                          <option value="work-permit">Work Permit</option>
                          <option value="visitor">Visitor</option>
                          <option value="other">Other</option>
                        </select>
                        <div v-if="form.errors.citizenship_status" class="invalid-feedback">
                          {{ form.errors.citizenship_status }}
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <CountryAutocomplete
                          id="country_of_birth"
                          label="Country of Birth"
                          v-model="form.country_of_birth"
                          :countries="countries"
                          :error="form.errors.country_of_birth"
                          placeholder="Type to search countries..."
                        />
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="language_spoken_at_home" class="form-label">Language Spoken at Home</label>
                      <input
                        type="text"
                        id="language_spoken_at_home"
                        v-model="form.language_spoken_at_home"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.language_spoken_at_home }"
                      />
                      <div v-if="form.errors.language_spoken_at_home" class="invalid-feedback">
                        {{ form.errors.language_spoken_at_home }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Accessibility Information -->
              <div class="col-lg-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Accessibility Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          id="disability_status"
                          v-model="form.disability_status"
                          class="form-check-input"
                          :class="{ 'is-invalid': form.errors.disability_status }"
                        />
                        <label for="disability_status" class="form-check-label">
                          I require accessibility accommodations
                        </label>
                        <div v-if="form.errors.disability_status" class="invalid-feedback">
                          {{ form.errors.disability_status }}
                        </div>
                      </div>
                    </div>
                    <div class="mb-3" v-if="form.disability_status">
                      <label for="accommodation_needs" class="form-label">Accommodation Details</label>
                      <textarea
                        id="accommodation_needs"
                        v-model="form.accommodation_needs"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.accommodation_needs }"
                        rows="3"
                        placeholder="Please describe your accommodation needs..."
                      ></textarea>
                      <div v-if="form.errors.accommodation_needs" class="invalid-feedback">
                        {{ form.errors.accommodation_needs }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bottom Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
              <Link href="/student/profile" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-2"></i>
                Cancel
              </Link>
              <button 
                type="submit" 
                class="btn btn-primary"
                :disabled="form.processing"
              >
                <i class="bi bi-floppy me-2"></i>
                {{ form.processing ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3'
import { format } from 'date-fns'
import AuthenticatedLayout from '../../Layouts/Authenticated.vue'
import CountryAutocomplete from '@/Components/CountryAutocomplete.vue'

export default {
  name: 'StudentProfileEdit',
  components: {
    Link,
    AuthenticatedLayout,
    CountryAutocomplete
  },
  props: {
    individual: {
      type: Object,
      required: true
    },
    countries: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  setup(props) {
    const form = useForm({
      // Identity Numbers
      social_insurance_number: props.individual.social_insurance_number || '',
      government_issued_id: props.individual.government_issued_id || '',
      
      // Name & Contact
      first_name: props.individual.first_name || '',
      middle_name: props.individual.middle_name || '',
      last_name: props.individual.last_name || '',
      preferred_name: props.individual.preferred_name || '',
      email_address: props.individual.email_address || '',
      phone_number: props.individual.phone_number || '',
      alternate_phone_number: props.individual.alternate_phone_number || '',
      
      // Demographics
      date_of_birth: props.individual.date_of_birth ? props.individual.date_of_birth.split('T')[0] : '',
      gender: props.individual.gender || '',
      preferred_pronouns: props.individual.preferred_pronouns || '',
      citizenship_status: props.individual.citizenship_status || '',
      country_of_birth: props.individual.country_of_birth || '',
      language_spoken_at_home: props.individual.language_spoken_at_home || '',
      
      // Current address
      current_street_address: props.individual.current_street_address || '',
      current_apartment_unit: props.individual.current_apartment_unit || '',
      current_city: props.individual.current_city || '',
      current_province_state: props.individual.current_province_state || '',
      current_postal_code: props.individual.current_postal_code || '',
      current_country: props.individual.current_country || '',
      
      // Mailing address
      use_different_mailing_address: props.individual.use_different_mailing_address || false,
      mailing_street_address: props.individual.mailing_street_address || '',
      mailing_apartment_unit: props.individual.mailing_apartment_unit || '',
      mailing_city: props.individual.mailing_city || '',
      mailing_province_state: props.individual.mailing_province_state || '',
      mailing_postal_code: props.individual.mailing_postal_code || '',
      mailing_country: props.individual.mailing_country || '',
      
      // Permanent address
      permanent_street_address: props.individual.permanent_street_address || '',
      permanent_apartment_unit: props.individual.permanent_apartment_unit || '',
      permanent_city: props.individual.permanent_city || '',
      permanent_province_state: props.individual.permanent_province_state || '',
      permanent_postal_code: props.individual.permanent_postal_code || '',
      permanent_country: props.individual.permanent_country || '',
      
      // Emergency contact
      emergency_contact: props.individual.emergency_contact || {
        name: '',
        relationship: '',
        phone: '',
        email: ''
      },
      
      // Health & Accessibility
      disability_status: props.individual.disability_status || false,
      accommodation_needs: props.individual.accommodation_needs || '',
      
      // Status & Metadata
      status: props.individual.status || 'active',
      verification_status: props.individual.verification_status || 'unverified',
      metadata: props.individual.metadata || {}
    })

    const submit = () => {
      form.put(('/student/profile'), {
        onSuccess: () => {
          // Handle success
        },
        onError: (errors) => {
          console.error('Form submission errors:', errors)
        }
      })
    }

    const formatDate = (date) => {
      if (!date) return 'N/A'
      return format(new Date(date), 'MMM d, yyyy')
    }

    return {
      form,
      submit,
      formatDate
    }
  }
}
</script>
