<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { ProfileHeader, SearchInput, NotesSection } from '@/components/ui';
import GradientSelector from '@/components/ui/gradient-selector/GradientSelector.vue';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { computed, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { nextTick } from 'vue';
import { PROFILE_GRADIENTS } from '@/constants/gradients';
import type { UserPageProps, UserDetails } from '@/types/user';

const page = usePage<UserPageProps>();
const user = page.props.user as UserDetails;
const formLabels = page.props.formLabels;

const entityData = computed(() => ({
    id: user.id,
    name: user.name,
    email: user.email || '',
    phone: user.phone,
    avatar: user.avatar,
    address: fullAddress.value
}));

const fullAddress = computed(() => {
    const parts = [];
    if (user.address) parts.push(user.address);
    if (user.city) parts.push(user.city);
    if (user.county) parts.push(user.county);
    if (user.country) parts.push(user.country);
    return parts.filter(part => part).join(', ') || formLabels.messages.no_address_available;
});

const formattedDateOfBirth = computed(() => {
    if (!user.birth_date) return '-';
    const date = new Date(user.birth_date);
    return date instanceof Date && !isNaN(date.valueOf()) ? date.toLocaleDateString('ro-RO') : '-';
});

const handleBack = () => {
    router.visit(route('users.index'));
};

const handleEditUser = (userId: number) => {
    router.visit(route('users.form', { id: userId }));
};

const currentProfileGradient = ref(user.profile_gradient_preference || 0);
const isGradientSelectorOpen = ref(false);
const profileHeaderKey = ref(0); 

const currentGradientClass = computed(() => {
    const gradientClass = PROFILE_GRADIENTS[currentProfileGradient.value]?.class || PROFILE_GRADIENTS[0].class;
    console.log('Computed gradient class:', gradientClass, 'for index:', currentProfileGradient.value);
    return gradientClass;
});

const openGradientSelector = () => {
    isGradientSelectorOpen.value = true;
};

const closeGradientSelector = () => {
    currentProfileGradient.value = user.profile_gradient_preference || 0;
    profileHeaderKey.value++; 
    isGradientSelectorOpen.value = false;
};

const closeGradientSelectorWithoutReset = () => {
    isGradientSelectorOpen.value = false;
};

const selectGradient = async (index: number) => {
    currentProfileGradient.value = index;
    profileHeaderKey.value++; 
    
    try {
        console.log('Saving gradient preference:', index);
        await router.post(route('users.save-preference'), {
            user_id: user.id,
            preference_type: 'profile_gradient',
            preference_value: index
        }, {
            preserveScroll: true,
            preserveState: true
        });
        console.log('Gradient saved successfully:', index);
        
        user.profile_gradient_preference = index;
        
    } catch (error) {
        console.log('Could not save preference:', error);
    }
};

const previewGradient = async (index: number) => {
    console.log('Preview gradient called with index:', index);
    console.log('Current gradient before change:', currentProfileGradient.value);
    currentProfileGradient.value = index;
    profileHeaderKey.value++; 
    console.log('Current gradient after change:', currentProfileGradient.value);
    console.log('Gradient class that should be applied:', PROFILE_GRADIENTS[index].class);
    
    await nextTick();
};

</script>

<template>
    <Head :title="`${formLabels.headings.user_details}: ${user.name}`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center mb-6">
                <Button variant="ghost" size="sm" @click="handleBack" class="mr-4 p-2">
                    <ArrowLeftIcon class="w-4 h-4" />
                </Button>
                <h1 class="text-xl font-semibold dark:text-white">{{ formLabels.headings.user_details }}</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <ProfileHeader 
                        :key="profileHeaderKey"
                        :entity="entityData"
                        :gradient="currentGradientClass"
                        :show-gradient-button="true"
                        @open-gradient-selector="openGradientSelector"
                    />

                    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ formLabels.headings.user_details }}</h3>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ formLabels.labels.role }}</span>
                                <span :class="user.is_admin ? 'bg-red-100 text-red-800 border-red-200' : 'bg-blue-50 text-blue-700 border-blue-200'" 
                                      class="px-3 py-1.5 rounded-lg text-sm font-semibold border shadow-sm">
                                    {{ user.is_admin ? formLabels.labels.administrator : formLabels.labels.user }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.headings.general_info }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div v-if="user.birth_date" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.birth_date }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ formattedDateOfBirth }}</span>
                                    </div>
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.registered_at }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ new Date(user.created_at).toLocaleDateString('ro-RO') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.headings.permissions }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div v-if="user.permissions && Object.keys(user.permissions).length > 0">
                                        <div class="h-32 overflow-y-scroll pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-800">
                                            <Accordion type="multiple" class="w-full">
                                                <AccordionItem v-for="(categoryPermissions, category) in user.permissions" :key="category" :value="category">
                                                    <AccordionTrigger class="text-sm font-medium">
                                                        {{ category }}
                                                        <span class="ml-2 text-xs text-gray-500">({{ categoryPermissions.length }})</span>
                                                    </AccordionTrigger>
                                                    <AccordionContent>
                                                        <div class="flex flex-wrap gap-2 pt-2">
                                                            <Badge v-for="permission in categoryPermissions" :key="permission" variant="secondary" class="text-xs">
                                                                {{ permission }}
                                                            </Badge>
                                                        </div>
                                                    </AccordionContent>
                                                </AccordionItem>
                                            </Accordion>
                                        </div>
                                    </div>
                                    <div v-else class="h-32 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400">
                                        {{ formLabels.messages.no_permissions }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.headings.actions }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <Button @click="handleEditUser(user.id)" size="lg" class="w-full">
                                        {{ formLabels.buttons.edit_user }}
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ formLabels.headings.invoices }}</h3>
                        </div>
                        
                        <div class="mb-4">
                            <SearchInput 
                                :placeholder="formLabels.placeholders.search_invoices"
                            />
                        </div>

                        <div class="text-center p-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ formLabels.messages.invoices_functionality }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <NotesSection 
                        :content="user.notes"
                        :default-text="formLabels.messages.no_notes_available"
                    />

                </div>
            </div>
        </div>

        <GradientSelector
            :is-open="isGradientSelectorOpen"
            :current-gradient="currentProfileGradient"
            :gradients="PROFILE_GRADIENTS"
            :user-initial="user.name.charAt(0).toUpperCase()"
            @close="closeGradientSelector"
            @select="selectGradient"
            @preview="previewGradient"
            @apply-and-close="closeGradientSelectorWithoutReset"
        />
    </AppLayout>
</template>