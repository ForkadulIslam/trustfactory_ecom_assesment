<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { format } from 'date-fns';

defineProps<{
    orders: {
        data: Array<{
            id: number;
            total_price: number;
            status: string;
            created_at: string;
            order_items: Array<{
                id: number;
                product: {
                    name: string;
                };
                quantity: number;
                price: number;
            }>;
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Orders',
        href: '/orders',
    },
];

const formatDate = (dateString: string) => {
    return format(new Date(dateString), 'PPP'); // e.g., "Oct 27, 2023"
};
</script>

<template>
    <Head title="Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h1 class="text-2xl font-semibold mb-4">Your Orders</h1>

            <div v-if="orders.data.length === 0" class="text-center text-muted-foreground">
                <p>You haven't placed any orders yet.</p>
            </div>

            <div v-else class="space-y-6">
                <div v-for="order in orders.data" :key="order.id" class="border rounded-lg p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4 border-b pb-3">
                        <div>
                            <h2 class="text-xl font-bold">Order #{{ order.id }}</h2>
                            <p class="text-sm text-muted-foreground">Placed on {{ formatDate(order.created_at) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold">Total: ${{ order.total_price.toFixed(2) }}</p>
                            <span :class="{
                                'bg-green-100 text-green-800': order.status === 'completed',
                                'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                'bg-red-100 text-red-800': order.status === 'cancelled',
                            }" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize">
                                {{ order.status }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div v-for="item in order.order_items" :key="item.id" class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="font-semibold">{{ item.product.name }}</span>
                                <span class="text-muted-foreground">x {{ item.quantity }}</span>
                            </div>
                            <p>${{ (item.price * item.quantity).toFixed(2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="orders.links.length > 3" class="flex justify-center mt-6">
                    <nav class="flex items-center space-x-1" aria-label="Pagination">
                        <Link
                            v-for="(link, key) in orders.links"
                            :key="key"
                            :href="link.url || '#'"
                            :class="{
                                'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0': true,
                                'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active,
                                'cursor-not-allowed opacity-50': !link.url,
                            }"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
