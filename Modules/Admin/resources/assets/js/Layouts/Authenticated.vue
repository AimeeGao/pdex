<template>
    <div class="d-flex flex-column min-vh-100">
        <AdminHeader />

        <!-- Sidebar Toggle Button (Mobile Only) -->
        <button 
            class="btn btn-primary d-lg-none position-fixed sidebar-toggle"
            type="button"
            @click="toggleSidebar"
            style="top: 80px; left: 15px; z-index: 1040;"
        >
            <i class="bi bi-list"></i>
        </button>

        <!-- Main Content Area with Sidebar -->
        <div class="flex-grow-1 d-flex position-relative">
            <!-- Sidebar Overlay (Mobile Only) -->
            <div 
                class="sidebar-overlay d-lg-none"
                :class="{ 'show': sidebarVisible }"
                @click="closeSidebar"
            ></div>

            <!-- Permanent Sidebar Menu -->
            <aside 
                class="admin-sidebar bg-white border-end" 
                :class="{ 'show': sidebarVisible }"
            >
                <div class="p-3 pe-0 ps-0">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3 ps-3">Admin Menu</h6>
                    <AdminMenu />
                </div>
            </aside>

            <!-- Page Content -->
            <main class="flex-grow-1 p-4 admin-main-content">
                <slot />
            </main>
        </div>

        <!-- BC Government Footer -->
        <Footer />
    </div>
</template>
<script>
import AdminHeader from '../Components/Header.vue'
import AdminMenu from '../Components/Menu.vue'
import { Footer } from '@/Components/BCDesign/Footer'
import { ref } from 'vue'

export default {
    name: 'Authenticated',
    components: {
        AdminHeader,
        AdminMenu,
        Footer
    },
    setup() {
        const sidebarVisible = ref(false)

        const toggleSidebar = () => {
            sidebarVisible.value = !sidebarVisible.value
        }

        const closeSidebar = () => {
            sidebarVisible.value = false
        }

        return {
            sidebarVisible,
            toggleSidebar,
            closeSidebar
        }
    }
}
</script>

<style scoped>
.admin-sidebar {
    width: 250px;
    box-shadow: 2px 0 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    height: fit-content;
    min-height: calc(100vh - 120px); /* Adjust based on header/footer height */
    transition: transform 0.3s ease-in-out;
}

/* Mobile responsive sidebar */
@media (max-width: 991.98px) {
    .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1030;
        transform: translateX(-100%);
        width: 280px;
    }
    
    .admin-sidebar.show {
        transform: translateX(0);
    }
    
    .admin-main-content {
        width: 100%;
        margin-left: 0;
    }
}

/* Sidebar overlay for mobile */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1025;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
}

.sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Sidebar toggle button */
.sidebar-toggle {
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.sidebar-toggle i {
    font-size: 1.2rem;
}

.admin-sidebar .list-group-item {
    border: none;
    border-radius: 6px;
    margin-bottom: 2px;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.admin-sidebar .list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

.admin-sidebar .list-group-item.active {
    background-color: #0d6efd;
    color: white;
}

.admin-sidebar .list-group-item i {
    margin-right: 0.5rem;
    width: 20px;
    text-align: center;
}
</style>
