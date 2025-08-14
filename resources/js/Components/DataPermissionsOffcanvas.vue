<template>
  <div 
    class="offcanvas offcanvas-end" 
    tabindex="-1" 
    :id="offcanvasId"
    :aria-labelledby="`${offcanvasId}Label`"
  >
    <div class="offcanvas-header border-bottom">
      <div>
        <h5 class="offcanvas-title" :id="`${offcanvasId}Label`">
          <i class="bi bi-shield-check me-2 text-primary"></i>
          Data Access Permissions
        </h5>
        <!-- <div class="text-muted small">{{ applicationName }}</div> -->
      </div>
      <button 
        type="button" 
        class="btn-close" 
        data-bs-dismiss="offcanvas" 
        aria-label="Close"
      ></button>
    </div>
    
    <div class="offcanvas-body">
      <div v-if="permissionGroups.length === 0" class="text-center py-5">
        <div class="text-muted">
          <i class="bi bi-info-circle fs-3 mb-3 d-block"></i>
          <p>No specific data permissions have been configured for this application.</p>
        </div>
      </div>
      
      <div v-else>
        <div class="alert alert-light border-0 mb-4">
          <div class="d-flex align-items-start">
            <i class="bi bi-info-circle text-primary me-2 mt-1"></i>
            <div class="small">
              <strong>Data Access Summary:</strong><br>
              This application can access the following data categories when you use it. 
              All data access is subject to your consent and privacy controls.
            </div>
          </div>
        </div>

        <div class="mb-4">
          <h6 class="text-muted mb-3 small text-uppercase">
            <i class="bi bi-list-ul me-2"></i>Data Categories
          </h6>
          
          <div 
            v-for="(group, index) in permissionGroups" 
            :key="group.table_name"
            class="card border-0 shadow-sm mb-3"
          >
            <div class="card-header bg-light border-0 py-3">
              <div class="d-flex align-items-center">
                <div 
                  class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                  :class="getTableIconClass(group.table_name)"
                  style="width: 40px; height: 40px;"
                >
                  <i :class="getTableIcon(group.table_name)" class="fs-5"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1 fw-semibold">{{ group.table_label }}</h6>
                  <div class="text-muted small">
                    {{ group.permissions.length }} field{{ group.permissions.length !== 1 ? 's' : '' }} accessible
                  </div>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                  {{ group.permissions.length }}
                </span>
              </div>
            </div>
            
            <div class="card-body py-3">
              <div class="row g-2">
                <div 
                  v-for="permission in group.permissions" 
                  :key="permission.column_name"
                  class="col-12"
                >
                  <div class="d-flex align-items-center p-2 bg-light bg-opacity-50 rounded">
                    <div class="flex-grow-1">
                      <div class="fw-medium small">
                        {{ permission.display_name || formatColumnName(permission.column_name) }}
                      </div>
                      <!-- <div class="text-muted" style="font-size: 0.75rem;">
                        {{ permission.column_name }}
                      </div> -->
                    </div>
                    <div class="text-end">
                      <span 
                        v-if="permission.can_read" 
                        class="badge bg-success bg-opacity-10 text-success me-1"
                        style="font-size: 0.65rem;"
                      >
                        <i class="bi bi-eye me-1"></i>Read
                      </span>
                      <span 
                        v-if="permission.can_write" 
                        class="badge bg-warning bg-opacity-10 text-warning"
                        style="font-size: 0.65rem;"
                      >
                        <i class="bi bi-pencil me-1"></i>Write
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Summary Footer -->
        <div class="mt-auto pt-3 border-top">
          <div class="text-center">
            <div class="small text-muted mb-2">
              <i class="bi bi-shield-check me-1"></i>
              <strong>Total: {{ totalPermissionsCount }} data field{{ totalPermissionsCount !== 1 ? 's' : '' }}</strong>
            </div>
            <div class="small text-muted">
              Data access is managed according to privacy regulations and your consent preferences.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  offcanvasId: {
    type: String,
    required: true
  },
  applicationName: {
    type: String,
    required: true
  },
  permissionGroups: {
    type: Array,
    default: () => []
  }
})

const totalPermissionsCount = computed(() => {
  return props.permissionGroups.reduce((total, group) => total + group.permissions.length, 0)
})

const getTableIcon = (tableName) => {
  const icons = {
    'individuals': 'bi-person-circle',
    'individual_addresses': 'bi-geo-alt',
    'individual_employments': 'bi-briefcase',
    'individual_identities': 'bi-person-badge'
  }
  return icons[tableName] || 'bi-table'
}

const getTableIconClass = (tableName) => {
  const classes = {
    'individuals': 'bg-primary bg-opacity-10 text-primary',
    'individual_addresses': 'bg-success bg-opacity-10 text-success', 
    'individual_employments': 'bg-warning bg-opacity-10 text-warning',
    'individual_identities': 'bg-info bg-opacity-10 text-info'
  }
  return classes[tableName] || 'bg-secondary bg-opacity-10 text-secondary'
}

const formatColumnName = (columnName) => {
  return columnName
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}
</script>

<style scoped>
.offcanvas {
  width: 420px;
}

@media (max-width: 576px) {
  .offcanvas {
    width: 100vw;
  }
}

.card:hover {
  transform: translateY(-1px);
  transition: transform 0.2s ease;
}
</style>
