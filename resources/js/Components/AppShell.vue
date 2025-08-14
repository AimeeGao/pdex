<script>
import { usePage } from '@inertiajs/vue3';

export default {
    name: 'AppShell',
    props: {
        variant: {
            type: String,
            default: 'header',
            validator: (value) => ['header', 'sidebar'].includes(value)
        }
    },
    setup() {
        const isOpen = usePage().props.sidebarOpen;
        
        return {
            isOpen
        };
    }
};
</script>

<template>
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
        <slot />
    </div>
    <div v-else class="flex">
        <slot />
    </div>
</template>
