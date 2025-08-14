<template>
  <AuthenticatedLayout>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">My Profile</h1>
            <div class="btn-group">
              <Link
                href="/student/profile/edit"
                class="btn btn-primary"
                v-if="individual"
              >
                <i class="bi bi-pencil-square me-2"></i>
                Edit Profile
              </Link>
              <Link
                href="/student/profile/create"
                class="btn btn-success"
                v-else
              >
                <i class="bi bi-plus-lg me-2"></i>
                Create Profile
              </Link>
            </div>
          </div>

          <!-- Profile Status Alert -->
          <div class="alert alert-warning mb-4" v-if="!individual">
            <div class="d-flex align-items-center">
              <i class="bi bi-exclamation-triangle me-2"></i>
              <div>
                <h5 class="alert-heading mb-1">Profile Not Found</h5>
                <p class="mb-0">You haven't created your profile yet. Please create your profile to access all features.</p>
              </div>
            </div>
          </div>

          <!-- Profile Content -->
          <div v-if="individual">
            <!-- Profile Header -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h2 class="mb-1">{{ individual.display_name }}</h2>
                    <p class="text-muted mb-2">{{ individual.full_name }}</p>
                    <div class="d-flex align-items-center gap-3">
                      <span class="badge" :class="statusBadgeClass">
                        {{ individual.status.charAt(0).toUpperCase() + individual.status.slice(1) }}
                      </span>
                      <span class="badge" :class="verificationBadgeClass">
                        {{ individual.verification_status.charAt(0).toUpperCase() + individual.verification_status.slice(1) }}
                      </span>
                    </div>
                  </div>
                  <div class="col-md-4 text-md-end">
                    <div class="text-muted small">
                      <div><strong>Last Updated:</strong> {{ formatDate(individual.updated_at) }}</div>
                      <div v-if="individual.last_login_at">
                        <strong>Last Login:</strong> {{ formatDate(individual.last_login_at) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Profile Sections -->
            <div class="row">
              <!-- Identity Information -->
              <div class="col-lg-6 mb-4">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Identity Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="row mb-3">
                      <div class="col-sm-6">
                        <strong>Social Insurance Number: <span class="text-danger">*</span></strong>
                        <div>{{ individual.social_insurance_number || 'Not provided' }}</div>
                      </div>
                    </div>
                    <div class="row mb-3" v-if="individual.government_issued_id">
                      <div class="col-sm-6">
                        <strong>Government Issued ID:</strong>
                        <div>{{ individual.government_issued_id }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Personal Information -->
              <div class="col-lg-6 mb-4">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Personal Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="row mb-3">
                      <div class="col-sm-6">
                        <strong>First Name:</strong>
                        <div>{{ individual.first_name }}</div>
                      </div>
                      <div class="col-sm-6">
                        <strong>Last Name:</strong>
                        <div>{{ individual.last_name }}</div>
                      </div>
                    </div>
                    <div class="row mb-3" v-if="individual.middle_name">
                      <div class="col-sm-6">
                        <strong>Middle Name:</strong>
                        <div>{{ individual.middle_name }}</div>
                      </div>
                    </div>
                    <div class="row mb-3" v-if="individual.preferred_name">
                      <div class="col-sm-6">
                        <strong>Preferred Name:</strong>
                        <div>{{ individual.preferred_name }}</div>
                      </div>
                    </div>
                    <div class="row mb-3" v-if="individual.date_of_birth">
                      <div class="col-sm-6">
                        <strong>Date of Birth:</strong>
                        <div>{{ formatDate(individual.date_of_birth) }}</div>
                      </div>
                    </div>
                    <div class="row mb-3" v-if="individual.gender">
                      <div class="col-sm-6">
                        <strong>Gender:</strong>
                        <div>{{ individual.gender }}</div>
                      </div>
                    </div>
                    <div class="row mb-3" v-if="individual.preferred_pronouns">
                      <div class="col-sm-6">
                        <strong>Pronouns:</strong>
                        <div>{{ individual.preferred_pronouns }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact Information -->
              <div class="col-lg-6 mb-4">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Contact Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <strong>Email:</strong>
                      <div class="d-flex align-items-center">
                        <span>{{ individual.email_address }}</span>
                        <span v-if="individual.email_verified_at" class="badge bg-success ms-2">
                          <i class="bi bi-check-circle me-1"></i>Verified
                        </span>
                        <span v-else class="badge bg-warning ms-2">
                          <i class="bi bi-exclamation-circle me-1"></i>Unverified
                        </span>
                      </div>
                    </div>
                    <div class="mb-3" v-if="individual.phone_number">
                      <strong>Phone:</strong>
                      <div>{{ individual.phone_number }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.alternate_phone_number">
                      <strong>Alternate Phone:</strong>
                      <div>{{ individual.alternate_phone_number }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Current Address -->
              <div class="col-lg-6 mb-4" v-if="hasCurrentAddress">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Current Address</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3" v-if="individual.current_street_address">
                      <strong>Street Address:</strong>
                      <div>{{ individual.current_street_address }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.current_apartment_unit">
                      <strong>Apartment/Unit:</strong>
                      <div>{{ individual.current_apartment_unit }}</div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-6" v-if="individual.current_city">
                        <strong>City:</strong>
                        <div>{{ individual.current_city }}</div>
                      </div>
                      <div class="col-sm-6" v-if="individual.current_province_state">
                        <strong>Province/State:</strong>
                        <div>{{ individual.current_province_state }}</div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-6" v-if="individual.current_postal_code">
                        <strong>Postal Code:</strong>
                        <div>{{ individual.current_postal_code }}</div>
                      </div>
                      <div class="col-sm-6" v-if="individual.current_country">
                        <strong>Country:</strong>
                        <div>{{ individual.current_country }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Mailing Address -->
              <div class="col-lg-6 mb-4" v-if="hasMailingAddress">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Mailing Address</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3" v-if="individual.mailing_street_address">
                      <strong>Street Address:</strong>
                      <div>{{ individual.mailing_street_address }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.mailing_apartment_unit">
                      <strong>Apartment/Unit:</strong>
                      <div>{{ individual.mailing_apartment_unit }}</div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-6" v-if="individual.mailing_city">
                        <strong>City:</strong>
                        <div>{{ individual.mailing_city }}</div>
                      </div>
                      <div class="col-sm-6" v-if="individual.mailing_province_state">
                        <strong>Province/State:</strong>
                        <div>{{ individual.mailing_province_state }}</div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-6" v-if="individual.mailing_postal_code">
                        <strong>Postal Code:</strong>
                        <div>{{ individual.mailing_postal_code }}</div>
                      </div>
                      <div class="col-sm-6" v-if="individual.mailing_country">
                        <strong>Country:</strong>
                        <div>{{ individual.mailing_country }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Permanent Address -->
              <div class="col-lg-6 mb-4" v-if="hasPermanentAddress">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Permanent Address</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3" v-if="individual.permanent_street_address">
                      <strong>Street Address:</strong>
                      <div>{{ individual.permanent_street_address }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.permanent_apartment_unit">
                      <strong>Apartment/Unit:</strong>
                      <div>{{ individual.permanent_apartment_unit }}</div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-6" v-if="individual.permanent_city">
                        <strong>City:</strong>
                        <div>{{ individual.permanent_city }}</div>
                      </div>
                      <div class="col-sm-6" v-if="individual.permanent_province_state">
                        <strong>Province/State:</strong>
                        <div>{{ individual.permanent_province_state }}</div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-6" v-if="individual.permanent_postal_code">
                        <strong>Postal Code:</strong>
                        <div>{{ individual.permanent_postal_code }}</div>
                      </div>
                      <div class="col-sm-6" v-if="individual.permanent_country">
                        <strong>Country:</strong>
                        <div>{{ individual.permanent_country }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Emergency Contact -->
              <div class="col-lg-6 mb-4" v-if="individual.emergency_contact">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Emergency Contact</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3" v-if="individual.emergency_contact.name">
                      <strong>Name:</strong>
                      <div>{{ individual.emergency_contact.name }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.emergency_contact.relationship">
                      <strong>Relationship:</strong>
                      <div>{{ individual.emergency_contact.relationship }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.emergency_contact.phone">
                      <strong>Phone:</strong>
                      <div>{{ individual.emergency_contact.phone }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.emergency_contact.email">
                      <strong>Email:</strong>
                      <div>{{ individual.emergency_contact.email }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Additional Information -->
              <div class="col-lg-6 mb-4" v-if="hasAdditionalInfo">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="mb-0">Additional Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3" v-if="individual.citizenship_status">
                      <strong>Citizenship Status:</strong>
                      <div>{{ individual.citizenship_status }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.country_of_birth">
                      <strong>Country of Birth:</strong>
                      <div>{{ individual.country_of_birth }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.language_spoken_at_home">
                      <strong>Language Spoken at Home:</strong>
                      <div>{{ individual.language_spoken_at_home }}</div>
                    </div>
                    <div class="mb-3" v-if="individual.disability_status">
                      <strong>Accessibility Support:</strong>
                      <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">
                          <i class="bi bi-universal-access-circle me-1"></i>Support Required
                        </span>
                      </div>
                      <div v-if="individual.accommodation_needs" class="mt-2">
                        <small class="text-muted">{{ individual.accommodation_needs }}</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Notes -->
              <div class="col-12 mb-4" v-if="individual.notes">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Notes</h5>
                  </div>
                  <div class="card-body">
                    <p class="mb-0">{{ individual.notes }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { format } from 'date-fns'
import AuthenticatedLayout from '../../Layouts/Authenticated.vue'

export default {
  name: 'StudentProfileIndex',
  components: {
    Link,
    AuthenticatedLayout
  },
  props: {
    individual: {
      type: Object,
      default: null
    }
  },
  computed: {
    statusBadgeClass() {
      const statusClasses = {
        'active': 'bg-success',
        'inactive': 'bg-secondary',
        'suspended': 'bg-danger'
      }
      return statusClasses[this.individual?.status] || 'bg-secondary'
    },
    verificationBadgeClass() {
      const verificationClasses = {
        'verified': 'bg-success',
        'pending': 'bg-warning',
        'unverified': 'bg-secondary',
        'rejected': 'bg-danger'
      }
      return verificationClasses[this.individual?.verification_status] || 'bg-secondary'
    },
    hasIdentityInfo() {
      return this.individual?.social_insurance_number ||
             this.individual?.government_issued_id
    },
    hasCurrentAddress() {
      return this.individual?.current_street_address ||
             this.individual?.current_city ||
             this.individual?.current_province_state ||
             this.individual?.current_postal_code ||
             this.individual?.current_country
    },
    hasMailingAddress() {
      return this.individual?.use_different_mailing_address && (
        this.individual?.mailing_street_address ||
        this.individual?.mailing_city ||
        this.individual?.mailing_province_state ||
        this.individual?.mailing_postal_code ||
        this.individual?.mailing_country
      )
    },
    hasPermanentAddress() {
      return this.individual?.permanent_street_address ||
             this.individual?.permanent_city ||
             this.individual?.permanent_province_state ||
             this.individual?.permanent_postal_code ||
             this.individual?.permanent_country
    },
    hasAdditionalInfo() {
      return this.individual?.citizenship_status ||
             this.individual?.country_of_birth ||
             this.individual?.language_spoken_at_home ||
             this.individual?.disability_status
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return 'N/A'
      return format(new Date(date), 'MMM d, yyyy')
    }
  }
}
</script>
