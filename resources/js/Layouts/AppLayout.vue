<script>
import { Header } from '@/Components/BCDesign/Header';
import { Footer } from '@/Components/BCDesign/Footer';

export default {
    name: 'AppLayout',
    components: {
        Header,
        Footer
    },
    props: {
        title: {
            type: String,
            default: 'Post Secondary Data Exchange'
        },
        showHeader: {
            type: Boolean,
            default: true
        },
        showFooter: {
            type: Boolean,
            default: true
        }
    }
};
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <!-- BC Government Header -->
        <Header 
            v-if="showHeader"
            :title="title" 
            href="/dashboard"
        >
            <template #navigation>
                <slot name="navigation" />
            </template>
        </Header>

        <!-- Main Content Area -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- BC Government Footer -->
        <Footer v-if="showFooter" />
    </div>
</template>

<style scoped>
/* Ensure proper layout structure */
.min-h-screen {
    min-height: 100vh;
}

/* Content area styling */
main {
    background-color: #f8fafc;
}

/* Override for specific page layouts */
.full-width {
    background-color: white;
}
</style>
