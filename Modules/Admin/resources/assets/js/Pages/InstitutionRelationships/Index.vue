<template>
    <Authenticated>
        <Head :title="`Relationships - ${institution.legal_operating_name}`" />
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
                        <li class="breadcrumb-item active" aria-current="page">Relationships</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold text-bc-blue mb-0">Institution Relationships</h1>
                        <p class="text-muted">Manage partnerships and relationships for {{ institution.legal_operating_name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <Link v-if="canManageInstitutions" class="btn btn-primary" :href="`/admin/institutions/${institution.id}/relationships/create`">
                            <i class="bi bi-plus-circle me-2"></i>Add New Relationship
                        </Link>
                        <Link class="btn btn-outline-secondary" :href="`/admin/institutions/${institution.id}`">
                            <i class="bi bi-arrow-left me-2"></i>Back to Institution
                        </Link>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-diagram-3 me-2"></i>Active Relationships
                                <span class="badge bg-secondary ms-2">{{ relationships?.data?.length || 0 }}</span>
                            </h5>
                            <div class="d-flex gap-2">
                                <div class="input-group" style="width: 300px;">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        placeholder="Search relationships..."
                                        v-model="searchQuery"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div v-if="filteredRelationships.length === 0" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-diagram-3 text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-muted">No Relationships Found</h5>
                                <p class="text-muted" v-if="searchQuery">
                                    No relationships match your search criteria.
                                </p>
                                <p class="text-muted" v-else>
                                    This institution doesn't have any relationships yet.
                                </p>
                                <Link 
                                    v-if="canManageInstitutions && !searchQuery" 
                                    class="btn btn-primary" 
                                    :href="`/admin/institutions/${institution.id}/relationships/create`"
                                >
                                    <i class="bi bi-plus-circle me-2"></i>Add First Relationship
                                </Link>
                            </div>
                            <div v-else class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Related Institution</th>
                                            <th scope="col">Reason</th>
                                            <th scope="col">Relationship Type</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="relationship in paginatedRelationships" :key="relationship.id">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">{{ relationship.related_institution.legal_operating_name }}</h6>
                                                        <small class="text-muted">{{ relationship.related_institution.institution_type }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span v-if="relationship.relationship_reason" class="text-muted small">
                                                    {{ relationship.relationship_reason }}
                                                </span>
                                                <span v-else class="text-muted">-</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ relationship.relationship_type }}</span>
                                            </td>
                                            <td>
                                                <span :class="getStatusBadgeClass(relationship.is_active)">
                                                    {{ relationship.is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <Link 
                                                        :href="`/admin/institutions/${institution.id}/relationships/${relationship.id}`"
                                                        class="btn btn-outline-primary"
                                                        title="View Details"
                                                    >
                                                        <i class="bi bi-eye"></i>
                                                    </Link>
                                                    <Link 
                                                        v-if="canManageInstitutions"
                                                        :href="`/admin/institutions/${institution.id}/relationships/${relationship.id}/edit`"
                                                        class="btn btn-outline-secondary"
                                                        title="Edit Relationship"
                                                    >
                                                        <i class="bi bi-pencil"></i>
                                                    </Link>
                                                    <button 
                                                        v-if="canManageInstitutions"
                                                        @click="toggleStatus(relationship)"
                                                        :class="relationship.is_active ? 'btn btn-outline-warning' : 'btn btn-outline-success'"
                                                        :title="relationship.is_active ? 'Deactivate' : 'Activate'"
                                                    >
                                                        <i :class="relationship.is_active ? 'bi bi-pause' : 'bi bi-play'"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-if="totalPages > 1" class="card-footer">
                            <nav aria-label="Relationships pagination">
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                        <button class="page-link" @click="currentPage = 1" :disabled="currentPage === 1">
                                            <i class="bi bi-chevron-double-left"></i>
                                        </button>
                                    </li>
                                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                        <button class="page-link" @click="currentPage--" :disabled="currentPage === 1">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                    </li>
                                    <li 
                                        v-for="page in visiblePages" 
                                        :key="page" 
                                        class="page-item" 
                                        :class="{ active: page === currentPage }"
                                    >
                                        <button class="page-link" @click="currentPage = page">{{ page }}</button>
                                    </li>
                                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                        <button class="page-link" @click="currentPage++" :disabled="currentPage === totalPages">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </li>
                                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                        <button class="page-link" @click="currentPage = totalPages" :disabled="currentPage === totalPages">
                                            <i class="bi bi-chevron-double-right"></i>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Authenticated>
</template>

<script>
import { Head, Link, router } from '@inertiajs/vue3'
import Authenticated from '../../Layouts/Authenticated.vue'
import { ref, computed } from 'vue'

export default {
    name: 'InstitutionRelationshipsIndex',
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
        relationships: {
            type: Object,
            default: () => ({ data: [] })
        },
        canManageInstitutions: {
            type: Boolean,
            default: false
        }
    },
    setup(props) {
        const searchQuery = ref('');
        const currentPage = ref(1);
        const itemsPerPage = ref(10);

        const filteredRelationships = computed(() => {
            const relationshipsData = props.relationships?.data || [];
            
            let filtered = relationshipsData;
            
            // Apply search filter if there's a query
            if (searchQuery.value) {
                const query = searchQuery.value.toLowerCase();
                filtered = relationshipsData.filter(relationship => {
                    const relatedName = relationship.related_institution?.legal_operating_name || '';
                    const relationshipType = relationship.relationship_type || '';
                    const relationshipReason = relationship.relationship_reason || '';
                    const status = relationship.is_active ? 'active' : 'inactive';
                    
                    return relatedName.toLowerCase().includes(query) ||
                           relationshipType.toLowerCase().includes(query) ||
                           relationshipReason.toLowerCase().includes(query) ||
                           status.includes(query);
                });
            }
            
            // Sort the relationships: first by type (A-Z), then by status (active first), then by institution name
            return filtered.sort((a, b) => {
                // First sort by relationship type (A-Z)
                const typeComparison = (a.relationship_type || '').localeCompare(b.relationship_type || '');
                if (typeComparison !== 0) {
                    return typeComparison;
                }
                
                // Then sort by status (active first)
                if (a.is_active !== b.is_active) {
                    return b.is_active - a.is_active; // true (1) comes before false (0)
                }
                
                // Finally sort by institution name (A-Z)
                const aName = a.related_institution?.legal_operating_name || '';
                const bName = b.related_institution?.legal_operating_name || '';
                return aName.localeCompare(bName);
            });
        });

        const totalPages = computed(() => {
            return Math.ceil(filteredRelationships.value.length / itemsPerPage.value);
        });

        const paginatedRelationships = computed(() => {
            const start = (currentPage.value - 1) * itemsPerPage.value;
            const end = start + itemsPerPage.value;
            return filteredRelationships.value.slice(start, end);
        });

        const visiblePages = computed(() => {
            const pages = [];
            const total = totalPages.value;
            const current = currentPage.value;
            
            let start = Math.max(1, current - 2);
            let end = Math.min(total, current + 2);
            
            if (end - start < 4) {
                if (start === 1) {
                    end = Math.min(total, start + 4);
                } else {
                    start = Math.max(1, end - 4);
                }
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        });

        const formatDate = (dateString) => {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('en-CA', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        };

        const getStatusBadgeClass = (isActive) => {
            return isActive ? 'badge bg-success' : 'badge bg-secondary';
        };

        const toggleStatus = (relationship) => {
            if (confirm('Are you sure you want to change the status of this relationship?')) {
                router.patch(`/admin/institutions/${props.institution.id}/relationships/${relationship.id}/toggle-status`, {}, {
                    preserveState: false,
                    onSuccess: () => {
                        // Success handled by controller
                    },
                    onError: (errors) => {
                        console.log('Toggle status errors:', errors);
                    }
                });
            }
        };

        return {
            searchQuery,
            currentPage,
            itemsPerPage,
            filteredRelationships,
            totalPages,
            paginatedRelationships,
            visiblePages,
            formatDate,
            getStatusBadgeClass,
            toggleStatus
        };
    }
}
</script>

<style scoped>
.text-bc-blue {
    color: #003366 !important;
}
</style>
