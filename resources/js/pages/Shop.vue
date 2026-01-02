<script setup lang="ts">
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { shop } from '@/routes';
import { type BreadcrumbItem, type Product } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { CheckCircle } from 'lucide-vue-next';

defineProps<{
    products: Product[];
}>();

const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shop',
        href: shop().url,
    },
];

const showSuccessAlert = ref(false);
const successMessage = ref('');
let successAlertTimeout: number | undefined;

const triggerSuccessAlert = (message: string) => {
    successMessage.value = message;
    showSuccessAlert.value = true;

    if (successAlertTimeout) {
        clearTimeout(successAlertTimeout);
    }

    successAlertTimeout = setTimeout(() => {
        showSuccessAlert.value = false;
        successMessage.value = '';
        successAlertTimeout = undefined;
    }, 3000);
};

const addToCart = (product: Product) => {
    router.post(`/cart/${product.id}`, {
        quantity: 1,
    }, {
        onSuccess: () => {
            triggerSuccessAlert(`${product.name} added to cart!`);
        },
        onError: (errors) => {
            console.error('Error adding to cart:', errors);
        },
    });
};

watch(() => page.props.flash?.success, (message) => {
    if (message) {
        triggerSuccessAlert(message as string);
    }
}, { immediate: true });
</script>

<template>
    <Head title="Shop" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <Alert
                v-if="showSuccessAlert"
                variant="default"
                class="fixed right-4 top-4 z-50 w-full max-w-sm"
            >
                <CheckCircle class="h-4 w-4" />
                <AlertTitle>Success!</AlertTitle>
                <AlertDescription>
                    {{ successMessage }}
                </AlertDescription>
            </Alert>
        </Transition>
        <div class="p-4">
            <div
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
            >
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="overflow-hidden rounded-lg border bg-card text-card-foreground shadow-sm"
                >

                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ product.name }}</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            ${{ (product.price / 100).toFixed(2) }}
                        </p>
                        <Button class="mt-4 w-full" @click="addToCart(product)">Add to Cart</Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
