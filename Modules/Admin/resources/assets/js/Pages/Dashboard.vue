<template>
    <Authenticated>
        <Head title="Admin Dashboard" />
        <div class="container-fluid px-4 py-4">
            <div class="mb-4">
                <h1 class="h2 fw-bold text-bc-blue">Admin Dashboard</h1>
                <p class="text-muted">Manage users, applications, and system settings</p>
            </div>
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="bi bi-people" style="font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <p class="card-text text-muted small mb-1">Total Users</p>
                                    <h4 class="card-title mb-0">{{ stats.totalUsers || 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success me-3">
                                    <i class="bi bi-person-check" style="font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <p class="card-text text-muted small mb-1">Active Users</p>
                                    <h4 class="card-title mb-0">{{ stats.activeUsers || 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="bi bi-clock" style="font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <p class="card-text text-muted small mb-1">Pending Approvals</p>
                                    <h4 class="card-title mb-0">{{ stats.pendingApprovals || 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger me-3">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <p class="card-text text-muted small mb-1">System Alerts</p>
                                    <h4 class="card-title mb-0">{{ stats.systemAlerts || 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="g-4">
                <div class="">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-bc-blue mb-3">Recent Activities</h5>
                            <div v-if="recentActivities && recentActivities.length > 0">
                                <div v-for="activity in recentActivities" :key="activity.id" class="d-flex align-items-start p-3 bg-light rounded mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="rounded-circle bg-bc-blue d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-info-circle text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="fw-medium mb-1">{{ activity.title }}</p>
                                        <p class="text-muted small mb-1">{{ activity.description }}</p>
                                        <p class="text-muted small mb-0">{{ formatDate(activity.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-5">
                                <i class="bi bi-clipboard-data text-muted mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted">No recent activities to show</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Authenticated>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import Authenticated from '../Layouts/Authenticated.vue';
import AdminMenu from '../Components/Menu.vue';

export default {
    name: 'AdminHome',
    components: {
        Head,
        Authenticated,
        AdminMenu
    },
    props: {
        stats: {
            type: Object,
            default: () => ({
                totalUsers: 0,
                activeUsers: 0,
                pendingApprovals: 0,
                systemAlerts: 0
            })
        },
        recentActivities: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('en-CA', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>

<style scoped>
.text-bc-blue {
    color: #003366 !important;
}
.bg-bc-blue {
    background-color: #003366 !important;
}
</style>