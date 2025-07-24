<script setup lang="ts">
import {
  Command,
  CommandDialog,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
  CommandSeparator,
} from '@/components/ui/command'
import { useClickOutside } from '@/composables/useClickOutside';
import axios from 'axios';
import { onBeforeMount, ref, watch } from 'vue';

const props = defineProps<{
    fetchUrl: string;
}>();

const products = ref([]);
const services = ref([]);

const emit = defineEmits<{
  (e: 'update:open', value: boolean): void
  (e: 'get', products: any[]): void
}>()

const isOpen = ref(!!props.open)
watch(() => props.open, v => (isOpen.value = !!v))

const onInput = (value: string) => {
    axios.get(props.fetchUrl, {
        params: { q: value }
    })
        .then(response => {
            const { products: fetchedProducts, services: fetchedServices } = response.data;
            products.value = fetchedProducts;
            services.value = fetchedServices;
        })
        .catch(error => {
            console.error('Error fetching products and services:', error);
        });
};

window.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        isOpen.value = false;
        emit('update:open', false);
    }
})

const returnProduct = (product: any) => {
    isOpen.value = false;
    emit('update:open', false);

    emit('get', product);
};

onBeforeMount(() => {
    axios.get(props.fetchUrl)
        .then(response => {
            const { products: fetchedProducts, services: fetchedServices } = response.data;
            products.value = fetchedProducts;
            services.value = fetchedServices;
        })
        .catch(error => {
            console.error('Error fetching products and services:', error);
        });
});

</script>

<template>
    <CommandDialog :open="isOpen">
        <CommandInput placeholder="Search products or services..." @update:model-value="onInput" />
        <CommandList>
            <CommandEmpty>No product or service found.</CommandEmpty>
            <CommandGroup heading="Products" v-if="products.length">
                <CommandItem v-for="product in products" :key="product.id" :value="product.id"  @select="returnProduct(product)">
                    <span class="font-semibold">{{ product.name }}</span>
                </CommandItem>
            </CommandGroup>
            <CommandSeparator />
            <CommandGroup heading="Services" v-if="services.length">
                <CommandItem v-for="service in services" :key="service.id" :value="service.id" @select="returnProduct(service)">
                    <span class="font-semibold">{{ service.name }}</span>
                </CommandItem>
            </CommandGroup>
        </CommandList>
    </CommandDialog>
</template>