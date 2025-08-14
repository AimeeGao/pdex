<script>
import {Link} from "@inertiajs/vue3";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Header } from '@/Components/BCDesign/Header';
import { Footer } from '@/Components/BCDesign/Footer';

export default {
    name: 'GuestLayout',
    components: {
        BreezeApplicationLogo, Link,
        Header, Footer
    }
}
</script>
<style scoped>
/* Main content styling to work with header and footer */
.bc-main-content {
    background-color: #f8fafc;
    min-height: calc(100vh - 80px - 200px); /* Account for header and footer */
    padding: 40px 16px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
}

.login-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    padding: 40px;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .login-card {
        padding: 24px;
        margin: 0 16px;
    }
    
    .bc-main-content {
        padding: 24px 0;
    }
}
</style>
<template>
    <!-- BC Government Header -->
    <Header title="Post Secondary Data Exchange" href="/">
        <template #title>
            <div class="d-flex align-items-center">
                <BreezeApplicationLogo width="126" height="34" class="me-3" />
                <span class="fs-5 fw-normal d-none d-lg-inline">
                    Post Secondary Data Exchange
                </span>
            </div>
        </template>
    </Header>

    <!-- Main Content Area -->
    <main class="bc-main-content flex-1">
        <slot />
    </main>

    <!-- BC Government Footer -->
    <Footer />
</template>
