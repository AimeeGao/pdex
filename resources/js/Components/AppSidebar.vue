<script>
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';

export default {
    name: 'AppSidebar',
    components: {
        NavFooter,
        NavMain,
        NavUser,
        Link,
        AppLogo
    },
    setup() {
        const mainNavItems = [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: 'LayoutGrid',
            },
        ];

        const footerNavItems = [
            {
                title: 'Github Repo',
                href: 'https://github.com/laravel/vue-starter-kit',
                icon: 'Folder',
            },
            {
                title: 'Documentation',
                href: 'https://laravel.com/docs/starter-kits#vue',
                icon: 'BookOpen',
            },
        ];
        
        return {
            mainNavItems,
            footerNavItems
        };
    }
};
</script>

<template>
    <aside class="w-64 bg-gray-800 text-white">
        <div class="p-4">
            <div class="mb-4">
                <Link :href="route('dashboard')">
                    <AppLogo />
                </Link>
            </div>
        </div>

        <div class="px-4">
            <NavMain :items="mainNavItems" />
        </div>

        <div class="mt-auto p-4">
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </div>
    </aside>
    <slot />
</template>
