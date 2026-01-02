<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { cart, dashboard, shop } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ShoppingCart, Package } from 'lucide-vue-next';

defineProps<{
    productCount: number;
    cartItemCount: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <Link :href="shop().url" class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <div class="flex items-center gap-2">
                        <Package class="size-6" />
                        <h3 class="text-lg font-semibold">Total Products</h3>
                    </div>
                    <p class="mt-4 text-4xl font-bold">{{ productCount }}</p>
                </Link>
                <Link :href="'/cart'" class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <div class="flex items-center gap-2">
                        <ShoppingCart class="size-6" />
                        <h3 class="text-lg font-semibold">Items in Your Cart</h3>
                    </div>
                    <p class="mt-4 text-4xl font-bold">{{ cartItemCount }}</p>
                </Link>
            </div>
            <div
                class="relative min-h-[60vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <!-- Other dashboard content can go here -->
            </div>
        </div>
    </AppLayout>
</template>
