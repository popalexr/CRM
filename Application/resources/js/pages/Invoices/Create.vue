<script setup lang="ts">
import ClientSearch from '@/components/ClientSearch.vue';
import InputError from '@/components/InputError.vue';
import ProductSelect from '@/components/ProductSelect.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Input from '@/components/ui/input/Input.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import axios from 'axios';
import { ArrowLeft, Plus, Trash, TriangleAlert } from 'lucide-vue-next';
import { computed, onBeforeMount, reactive, ref, watch } from 'vue';


const props = defineProps<{
  currencies: Record<string, string>
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Invoices',
        href: route('invoices.index'),
    },
    {
        title: 'Add Invoice',
        href: route('invoices.form'),
    },
];

const form = useForm({
    client_id: null,
    currency: '',
    payment_deadline: '',
    products: [],
    total: 0,
    total_no_vat: 0,
    vat_amount: 0,
});

const openProductModalState = ref(false);

const vats = ref([]);

const handleBack = () => {
    router.visit(route('invoices.index'));
};

const handleSubmit = () => {
    console.log(form);

    form.post(route('invoices.form.post'), {
        onSuccess: () => {
            router.visit(route('invoices.index'));
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};

const openProductModal = () => {
    openProductModalState.value = true;
};

const addProductToList = (product: any) => {
    if (form.products.some(p => p.product_id === product.id)) {
        return;
    }

    let productElement: any = reactive({
        id: product.id,
        name: product.name,
        price: parseFloat(product.price).toFixed(2),
        currency: product.currency,
        vat: parseFloat(vats.value.find(v => v.id === product.vat_id)?.rate) || null,
        quantity: null,
        type: product.type,
        unit: product.unit,
        converted_price: 0,
        converted_currency: '',
        vat_amount: computed(() => {
            return (parseFloat(productElement.total_no_vat) * parseFloat(productElement.vat || 0) / 100).toFixed(2);
        }),
        total_no_vat: computed(() => {
            return (parseFloat(productElement.converted_price) * parseFloat(productElement.quantity || 1)).toFixed(2);
        }),
        total: computed(() => {
            return (parseFloat(productElement.total_no_vat) + parseFloat(productElement.vat_amount)).toFixed(2);
        }),
    });

    axios.get(route('api.currencyRate', {
        currency_code: product.currency,
        value: productElement.price,
        to_currency_code: form.currency,
    }))
        .then(response => {
            productElement.converted_price = response.data.value.toFixed(2);
            productElement.converted_currency = response.data.currency_code;

            form.products.push(productElement);
        })
        .catch(error => {
            console.error('Error fetching currency rate:', error);
        });
};

const changeVat = (value: string, product: any) => {
    const vatRate = vats.value.find(v => v.id === value)?.rate || 0;
    product.vat = parseFloat(vatRate);
};

watch(() => form.currency, (newCurrency) => {
    form.products.forEach(product => {
        if (product.converted_currency !== newCurrency) {
            axios.get(route('api.currencyRate', {
                currency_code: product.currency,
                value: product.price,
                to_currency_code: newCurrency,
            }))
                .then(response => {
                    product.converted_price = response.data.value.toFixed(2);
                    product.converted_currency = response.data.currency_code;
                })
                .catch(error => {
                    console.error('Error fetching currency rate:', error);
                });
        }
    });
}, { immediate: true });

watch(() => form.products, (newProducts) => {
    form.total = newProducts.reduce((sum, product) => {
        return sum + parseFloat(product.total || 0);
    }, 0).toFixed(2);

    form.total_no_vat = newProducts.reduce((sum, product) => {
        return sum + parseFloat(product.total_no_vat || 0);
    }, 0).toFixed(2);

    form.vat_amount = newProducts.reduce((sum, product) => {
        return sum + parseFloat(product.vat_amount || 0);
    }, 0).toFixed(2);
}, { deep: true, immediate: true });

onBeforeMount(() => {
    axios.get(route('api.getVAT'))
        .then(response => {
            vats.value = response.data.vats;
        })
        .catch(error => {
            console.error('Error fetching VATs:', error);
        });
})

</script>

<template>
    <Head title="Add Invoice" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">Add Invoice</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Card>
                    <CardHeader>
                        <CardTitle>Invoice details</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <Label>Client</Label>
                                    <InputError :message="form.errors.client_id" />
                                </div>
                                <ClientSearch
                                    :fetchUrl="route('api.getClients')"
                                    placeholder="Search for a client..."
                                    v-model:model-value="form.client_id"
                                />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <Label for="currency">Currency</Label>
                                    <InputError :message="form.errors.currency" />
                                </div>
                                <Select v-model="form.currency" class="w-full">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select currency" />
                                    </SelectTrigger>
                                    <SelectContent class="w-full">
                                        <SelectGroup>
                                        <SelectItem
                                            v-for="(label, code) in props.currencies"
                                            :key="code"
                                            :value="code"
                                        >
                                            {{ label }} ({{ code }})
                                        </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                    </Select>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <Label for="payment_deadline">Payment Deadline</Label>
                                    <InputError :message="form.errors.payment_deadline" />
                                </div>
                                <Input 
                                    id="payment_deadline" 
                                    type="date"
                                    placeholder="Payment Deadline"
                                    v-model="form.payment_deadline"
                                />
                            </div>
                        </div>

                        <div class="mt-6" v-if="form.currency">
                            <Button 
                                type="button"
                                @click="openProductModal"
                                v-if="form.products.length > 0"
                            >
                                <Plus/>
                            </Button>
                            <Table v-if="form.products.length > 0">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Product name</TableHead>
                                        <TableHead>Price</TableHead>
                                        <TableHead>Quantity</TableHead>
                                        <TableHead>VAT</TableHead>
                                        <TableHead>Total without VAT</TableHead>
                                        <TableHead class="text-right">Total price</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="(product, index) in form.products" :key="index">
                                        <TableHead>
                                            <div class="flex items-center gap-2">
                                                <Button 
                                                    variant="ghost" 
                                                    size="icon" 
                                                    @click="form.products.splice(index, 1)"
                                                >
                                                    <Trash class="h-4 w-4 text-red" />
                                                </Button>
                                                <p>{{ product.name }}</p>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <p>{{  product.converted_price }} {{ product.converted_currency  }}</p>
                                        </TableHead>
                                        <TableHead>
                                            <Input v-model="product.quantity" type="number" :placeholder="product.unit" />
                                            <InputError :message="form.errors[`products.${index}.quantity`]" class="py-2" />
                                        </TableHead>
                                        <TableHead>
                                            <p v-if="product.vat !== null">{{ product.vat }}%</p>
                                            <Select @update:model-value="changeVat($event, product)" class="w-full" v-else>
                                                <SelectTrigger class="w-full">
                                                    <SelectValue placeholder="No VAT selected." />
                                                </SelectTrigger>
                                                <SelectContent class="min-w-0 w-full">
                                                    <SelectGroup>
                                                        <SelectItem 
                                                            v-for="vat in vats" 
                                                            :key="vat.id" 
                                                            :value="vat.id"
                                                        >
                                                            {{ vat.rate }}%
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>

                                            <InputError :message="form.errors[`products.${index}.vat`]" class="py-2" />
                                        </TableHead>
                                        <TableHead>
                                            <p v-if="product.vat !== null">
                                                {{ product.vat_amount }} {{ product.converted_currency }}
                                            </p>
                                        </TableHead>
                                        <TableHead class="text-right">
                                            <p v-if="product.vat !== null">
                                                {{ product.total }} {{ product.converted_currency }}
                                            </p>
                                            <p v-else>-</p>
                                        </TableHead>
                                    </TableRow>
                                    <TableRow>
                                        <TableHead colspan="4"></TableHead>
                                        <TableHead>
                                            <strong>Total without VAT</strong>
                                        </TableHead>
                                        <TableHead class="text-right">
                                            <p>{{ form.total_no_vat }} {{ form.currency }}</p>
                                        </TableHead>
                                    </TableRow>
                                    <TableRow>
                                        <TableHead colspan="4"></TableHead>
                                        <TableHead>
                                            <strong>VAT amount</strong>
                                        </TableHead>
                                        <TableHead class="text-right">
                                            <p>{{ form.vat_amount }} {{ form.currency }}</p>
                                        </TableHead>
                                    </TableRow>
                                    <TableRow>
                                        <TableHead colspan="4"></TableHead>
                                        <TableHead>
                                            <strong>Total</strong>
                                        </TableHead>
                                        <TableHead class="text-right">
                                            <p>{{ form.total }} {{ form.currency }}</p>
                                        </TableHead>
                                    </TableRow>
                                </TableBody>
                            </Table>
                            <div v-else class="text-center text-muted-foreground">
                                <div>No products added yet.</div>
                                <Button variant="outline" class="mt-4" @click="openProductModal" type="button">
                                    Add Product
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="mt-6 flex justify-end">
                    <Button type="submit" class="w-full md:w-auto">Create Invoice</Button>
                </div>
            </form>
        </div>

        <ProductSelect
            :fetchUrl="route('api.getProducts')"
            v-model:open="openProductModalState"
            @get="addProductToList"
        />
    </AppLayout>
</template>