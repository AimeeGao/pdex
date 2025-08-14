<template>
  <div class="min-vh-100 bg-light">
    <!-- Header Section with BC Gov Branding -->
    <header class="py-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="d-flex align-items-center">
              <BreezeApplicationLogo width="160" class="me-3" />
              <div>
                <h1 class="h3 mb-0 fw-light">PDEX</h1>
                <p class="mb-0 opacity-75">Post-Secondary Data Exchange</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 text-md-end">
            <a href="/login" class="btn btn-primary btn-lg px-4">
              <i class="bi bi-box-arrow-in-right me-2"></i>
              Login to PDEX
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="py-5 bg-gradient" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
      <div class="container">
        <div class="row align-items-center min-vh-50">
          <div class="col-lg-6 text-white">
            <h1 class="display-4 fw-bold mb-4">
              Connecting BC's Post-Secondary Future
            </h1>
            <p class="lead mb-4">
              The Post-Secondary Data Exchange (PDEX) platform streamlines student data sharing across British Columbia's 
              post-secondary institutions, making education more accessible and efficient for everyone.
            </p>
            <div class="d-flex flex-wrap gap-3">
              <a href="/login" class="btn btn-light btn-lg px-4">
                <i class="bi bi-rocket-takeoff me-2"></i>
                Get Started
              </a>
              <button class="btn btn-outline-light btn-lg px-4" @click="scrollToSection('about')">
                <i class="bi bi-info-circle me-2"></i>
                Learn More
              </button>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="text-center">
              <div class="card border-0 shadow-lg bg-white p-4">
                <div class="row text-center">
                  <div class="col-6 border-end">
                    <div class="p-3">
                      <h3 class="h2 text-primary fw-bold mb-0">{{ stats.institutions.toLocaleString() }}+</h3>
                      <p class="text-muted mb-0">Institutions</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="p-3">
                      <h3 class="h2 text-success fw-bold mb-0">{{ stats.students_served.toLocaleString() }}+</h3>
                      <p class="text-muted mb-0">Students Served</p>
                    </div>
                  </div>
                  <div class="col-6 border-end border-top">
                    <div class="p-3">
                      <h3 class="h2 text-info fw-bold mb-0">{{ stats.applications }}+</h3>
                      <p class="text-muted mb-0">Applications</p>
                    </div>
                  </div>
                  <div class="col-6 border-top">
                    <div class="p-3">
                      <h3 class="h2 text-warning fw-bold mb-0">{{ Math.floor(stats.data_exchanges / 1000) }}K+</h3>
                      <p class="text-muted mb-0">Data Exchanges</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="about" class="py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-4">Why Choose PDEX?</h2>
            <p class="lead text-muted">
              Built specifically for British Columbia's post-secondary education ecosystem, 
              PDEX provides secure, efficient, and transparent data exchange capabilities.
            </p>
          </div>
        </div>
        <div class="row g-4">
          <div v-for="feature in features" :key="feature.title" class="col-md-6 col-lg-3">
            <div class="card border-0 h-100 shadow-sm hover-lift">
              <div class="card-body text-center p-4">
                <div class="mb-3">
                  <i :class="feature.icon" class="text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="card-title fw-bold mb-3">{{ feature.title }}</h5>
                <p class="card-text text-muted">{{ feature.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Portals Section -->
    <section class="py-5 bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-4">Access Portals</h2>
            <p class="lead text-muted">
              Specialized portals designed for different user types within the BC post-secondary ecosystem.
            </p>
          </div>
        </div>
        <div class="row g-4">
          <div v-for="portal in portals" :key="portal.name" class="col-lg-4">
            <div class="card border-0 h-100 shadow hover-lift">
              <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                  <div :class="`bg-${portal.color} bg-opacity-10 text-${portal.color} rounded-3 p-3 me-3`">
                    <i :class="portal.icon" style="font-size: 1.5rem;"></i>
                  </div>
                  <h5 class="card-title fw-bold mb-0">{{ portal.name }}</h5>
                </div>
                <p class="card-text text-muted mb-4">{{ portal.description }}</p>
                <a href="/login" :class="`btn btn-${portal.color} w-100`">
                  Access Portal
                  <i class="bi bi-arrow-right ms-2"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Security & Privacy Section -->
    <section class="py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h2 class="display-5 fw-bold text-dark mb-4">Security & Privacy First</h2>
            <div class="row g-4">
              <div class="col-12">
                <div class="d-flex">
                  <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3 flex-shrink-0">
                    <i class="bi bi-lock" style="font-size: 1.25rem;"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-2">End-to-End Encryption</h6>
                    <p class="text-muted mb-0">All data transfers are secured with industry-standard encryption protocols.</p>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex">
                  <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3 flex-shrink-0">
                    <i class="bi bi-eye" style="font-size: 1.25rem;"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-2">Transparent Data Permissions</h6>
                    <p class="text-muted mb-0">Clear visibility into what data applications can access and how it's used.</p>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex">
                  <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3 flex-shrink-0">
                    <i class="bi bi-person-shield" style="font-size: 1.25rem;"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-2">Privacy Compliance</h6>
                    <p class="text-muted mb-0">Full compliance with BC's privacy legislation and educational data protection standards.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="text-center">
              <div class="position-relative">
                <div class="bg-primary bg-opacity-10 rounded-circle p-5" style="width: 300px; height: 300px; margin: 0 auto;">
                  <div class="bg-primary rounded-circle p-4 d-flex align-items-center justify-content-center h-100">
                    <i class="bi bi-shield-shaded text-white" style="font-size: 4rem;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 bg-primary text-white">
      <div class="container text-center">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">
              Join thousands of students, institutions, and administrators already using PDEX 
              to streamline their post-secondary data management.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-3">
              <a href="/login" class="btn btn-light btn-lg px-5">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Login to PDEX
              </a>
              <button class="btn btn-outline-light btn-lg px-5" @click="scrollToSection('contact')">
                <i class="bi bi-envelope me-2"></i>
                Contact Support
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-dark text-white py-5">
      <div class="container">
        <div class="row g-4">
          <div class="col-lg-4">
            <div class="d-flex align-items-center mb-3">
              <img src="/images/gov_bc_logo.svg" alt="Government of British Columbia" class="me-3" style="height: 30px;">
              <h5 class="mb-0">PDEX</h5>
            </div>
            <p class="text-white">
              Provincial Data Exchange platform for British Columbia's post-secondary education system.
            </p>
          </div>
          <div class="col-lg-2">
            <h6 class="fw-bold mb-3">Quick Links</h6>
            <ul class="list-unstyled">
              <li><a href="/login" class="text-white text-decoration-none hover-link">Login</a></li>
              <li><a href="#about" class="text-white text-decoration-none hover-link" @click="scrollToSection('about')">About</a></li>
              <li><a href="#contact" class="text-white text-decoration-none hover-link">Contact</a></li>
            </ul>
          </div>
          <div class="col-lg-3">
            <h6 class="fw-bold mb-3">Support</h6>
            <ul class="list-unstyled">
              <li class="text-white mb-2">
                <i class="bi bi-envelope me-2"></i>
                pdex.support@gov.bc.ca
              </li>
              <li class="text-white mb-2">
                <i class="bi bi-telephone me-2"></i>
                1-800-567-1195
              </li>
              <li class="text-white">
                <i class="bi bi-clock me-2"></i>
                Mon-Fri, 8:00 AM - 5:00 PM PST
              </li>
            </ul>
          </div>
          <div class="col-lg-3">
            <h6 class="fw-bold mb-3">Government of BC</h6>
            <ul class="list-unstyled">
              <li><a href="https://www2.gov.bc.ca/" class="text-white text-decoration-none hover-link" target="_blank">BC Government</a></li>
              <li><a href="https://www2.gov.bc.ca/gov/content/education-training" class="text-white text-decoration-none hover-link" target="_blank">Education & Training</a></li>
              <li><a href="https://www2.gov.bc.ca/gov/content/governments/about-the-bc-government/privacy" class="text-white text-decoration-none hover-link" target="_blank">Privacy Policy</a></li>
            </ul>
          </div>
        </div>
        <hr class="my-4 border-secondary">
        <div class="row align-items-center">
          <div class="col-md-6">
            <p class="text-white mb-0">
              © {{ new Date().getFullYear() }} Government of British Columbia. All rights reserved.
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p class="text-white mb-0">
              Built with security and privacy in mind.
            </p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";

// Props from controller
const props = defineProps({
  stats: {
    type: Object,
    required: true
  },
  features: {
    type: Array,
    required: true
  },
  portals: {
    type: Array,
    required: true
  }
})

// Methods
const scrollToSection = (sectionId) => {
  const element = document.getElementById(sectionId)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' })
  }
}

onMounted(() => {
  // Add smooth scrolling behavior
  document.documentElement.style.scrollBehavior = 'smooth'
})
</script>

<style scoped>
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.hover-link {
  transition: color 0.3s ease;
}

.hover-link:hover {
  color: #ffffff !important;
}

.min-vh-50 {
  min-height: 50vh;
}

.bg-gradient {
  background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important;
}

/* Custom button hover effects */
.btn {
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .display-4 {
    font-size: 2.5rem;
  }
  
  .display-5 {
    font-size: 2rem;
  }
  
  .lead {
    font-size: 1.1rem;
  }
}
</style>
