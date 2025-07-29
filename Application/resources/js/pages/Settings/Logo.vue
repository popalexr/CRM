<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import Dropzone from '@/components/Dropzone.vue';

const props = defineProps<{
    currentLogoUrl?: string | null;
}>();

const page = usePage();
const csrf_token = computed(() => page.props.csrf as string);

const form = useForm({
    logo_file_id: '',
});

const initialFile = ref<{ name: string, size: number, url: string } | null>(
    props.currentLogoUrl 
        ? { name: 'Logo Actual', size: 12345, url: props.currentLogoUrl } 
        : null
);

const handleSubmit = () => {
    form.post(route('settings.logo.store'), {
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};

const fileUploadSuccess = (payload: { response: any }) => {
    const responseData = payload.response;
    
    if (responseData && responseData.file_id) {
        form.logo_file_id = responseData.file_id;
        
        if (responseData.file_url) {
            initialFile.value = {
                name: responseData.file_name,
                size: responseData.file_size,
                url: responseData.file_url,
            };
        }
    }
};

const fileUploadError = (payload: { errorMessage: any }) => {
    console.error('File upload error:', payload.errorMessage);
};

const uploadedFileRemoved = () => {
    router.post(route('settings.logo.destroy'), {}, {
        preserveScroll: true,
        onSuccess: () => {
            form.logo_file_id = '';
            initialFile.value = null;
        },
        onError: (errors) => {
            console.error('Eroare la ștergerea logo-ului:', errors);
        }
    });
};
</script>

<template>
    <Head title="Update Logo Company" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Logo Company</h1>
                    <p class="text-muted-foreground"></p>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Card>
                    <CardHeader>
                        <CardTitle>Upload new Imagine</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <InputError :message="form.errors.logo_file_id" />
                        <Dropzone 
                            :url="route('upload.image')"
                            @success="fileUploadSuccess"
                            @error="fileUploadError"
                            @removed="uploadedFileRemoved"
                            :headers="{'X-CSRF-TOKEN': csrf_token }"
                            accepted-files="image/jpeg,image/png,image/jpg,image/svg+xml"
                            :initial-file="initialFile"
                        />
                    </CardContent>
                </Card>

                <div class="mt-6 flex justify-end">
                    <Button type="submit" :disabled="form.processing || !form.logo_file_id">
                        {{ form.processing ? 'Saving' : 'Save Logo' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>