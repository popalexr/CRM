<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import Dropzone from '@/components/Dropzone.vue';
import { BreadcrumbItem } from '@/types';
import { Building, ImageIcon, Receipt } from 'lucide-vue-next';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import TabsList from '@/components/ui/tabs/TabsList.vue';
import TabsContent from '@/components/ui/tabs/TabsContent.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: route('settings.index'),
    },
    {
        title: 'Logo Settings',
        href: route('settings.vat'),
    },
];

const page = usePage();
const csrf_token = computed(() => page.props.csrf as string);

const form = useForm({
    logo_file_id: '',
});

const showGeneralInfo = () => {
    router.visit(route('settings.index'));
};

const showVatForm = () => {
    router.visit(route('settings.vat'));
};


const handleSubmit = () => {
    router.visit(route('settings.logo.update'), {
        method: 'post',
        data: form
    });
};

const fileUploadSuccess = (payload: { response: any }) => {
    const responseData = payload.response;
    
    if (responseData && responseData.file_id) {
        form.logo_file_id = responseData.file_id;
    }
};

const fileUploadError = (payload: { errorMessage: any }) => {
    console.error('File upload error:', payload.errorMessage);
};

const uploadedFileRemoved = () => {
    form.logo_file_id = '';
};
</script>

<template>
    <Head title="VAT Rates" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                            <Settings class="h-6 w-6" />
                            Company Logo
                        </h1>
                    </div>
                </div>
            </div>

            <Tabs default-value="company_logo" class="w-full">
                <TabsList class="mb-6 bg-light">
                    <Button class="flex items-center gap-2 bg-secondary text-secondary-foreground hover:bg-secondary/10 mr-2" @click="showGeneralInfo">
                        <Building class="h-4 w-4" />
                        General Information
                    </Button>
                    <Button class="flex items-center gap-2 bg-secondary text-secondary-foreground hover:bg-secondary/10 mr-2" @click="showVatForm">
                        <Receipt class="h-4 w-4" />
                        VAT Rates
                    </Button>
                    <Button class="flex items-center gap-2 active" @click="router.visit(route('settings.logo'))">
                        <ImageIcon class="h-4 w-4" />
                        Logo
                    </Button>
                </TabsList>

                <!-- VAT Rates Tab -->
                <TabsContent value="company_logo">
                    <Card>
                        <CardHeader>
                            <CardTitle>
                                Company Logo
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <InputError :message="form.errors.logo_file_id" />
                            <Dropzone 
                                :url="route('upload.image')"
                                @success="fileUploadSuccess"
                                @error="fileUploadError"
                                @removed="uploadedFileRemoved"
                                :headers="{'X-CSRF-TOKEN': csrf_token }"
                                accepted-files="image/jpeg,image/png,image/jpg"
                            />
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>

            <div class="flex justify-end">
                <Button @click="handleSubmit" class="bg-primary text-primary-foreground hover:bg-primary/90">
                    Save Logo
                </Button>
            </div>
        </div>
    </AppLayout>
</template>