<script>
import { computed } from 'vue';

export default {
    name: 'AppContent',
    props: {
        variant: {
            type: String,
            default: 'header',
            validator: (value) => ['header', 'sidebar'].includes(value)
        },
        class: {
            type: String,
            default: ''
        }
    },
    setup(props) {
        const className = computed(() => props.class);
        
        return {
            className
        };
    }
};
</script>

<template>
    <div v-if="variant === 'sidebar'" :class="className">
        <slot />
    </div>
    <main v-else class="mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl" :class="className">
        <slot />
    </main>
</template>
