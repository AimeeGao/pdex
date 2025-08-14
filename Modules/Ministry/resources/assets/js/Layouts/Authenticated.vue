<template>
        <div class="min-vh-100 d-flex flex-column" style="background-color: #f8f9fa;">
        <!-- Header -->
        <MinistryHeader />

        <!-- Page Content -->
        <main class="flex-grow-1 container py-4">
            <slot />
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>
<script>
import MinistryHeader from '../Components/Header.vue'
import { Footer } from '@/Components/BCDesign/Footer'

export default {
    name: 'Authenticated',
    components: {
        MinistryHeader,
        Footer
    }
}
</script>