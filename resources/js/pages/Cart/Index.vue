<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Cart } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Trash2 } from 'lucide-vue-next';

defineProps<{
    cart: Cart;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cart',
        href: '/cart',
    },
];

const updateQuantity = (cartItem, newQuantity) => {
    if (newQuantity < 0) return;
    router.patch(`/cart/${cartItem.id}`, { quantity: newQuantity }, { preserveScroll: true });
};

const removeItem = (cartItem) => {
    router.delete(`/cart/${cartItem.id}`, { preserveScroll: true });
};

const total = (cart) => {
    if (!cart || !cart.cart_items) return 0;
    return cart.cart_items.reduce((acc, item) => acc + item.product.price * item.quantity, 0);
};

const confirmOrder = () => {
    router.post('/orders', {}, {
        onSuccess: () => {
            // Success handling can be added here, e.g., showing a notification.
            // The backend will redirect, and Inertia will update the page.
            console.log('Order is confired');
        },
        onError: (errors) => {
            console.error('Error creating order:', errors);
        },
    });
};
</script>

<template>
    <Head title="Cart" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h1 class="text-2xl font-semibold mb-4">Your Cart</h1>

            <div v-if="!cart || cart.cart_items.length === 0" class="text-center text-muted-foreground">
                <p>Your cart is empty.</p>
            </div>

            <div v-else class="space-y-4">
                <div v-for="item in cart.cart_items" :key="item.id" class="flex items-center justify-between p-4 border rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h2 class="font-semibold">{{ item.product.name }}</h2>
                            <p class="text-sm text-muted-foreground">
                                ${{ (item.product.price / 100).toFixed(2) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <Button variant="outline" size="sm" @click="updateQuantity(item, item.quantity - 1)">-</Button>
                            <Input type="number" class="w-16 text-center" :defaultValue="item.quantity" :key="item.quantity" @blur="updateQuantity(item, $event.target.value)" />
                            <Button variant="outline" size="sm" @click="updateQuantity(item, item.quantity + 1)">+</Button>
                        </div>
                        <Button variant="destructive" size="sm" @click="removeItem(item)">
                            <Trash2 class="h-4 w-4" />
                        </Button>
                        <p class="w-24 text-right font-semibold">
                            ${{ ((item.product.price * item.quantity) / 100).toFixed(2) }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <div class="text-right">
                        <p class="text-lg font-semibold">
                            Total: ${{ (total(cart) / 100).toFixed(2) }}
                        </p>
                        <Button class="mt-2" @click="confirmOrder">Confirm Order</Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
