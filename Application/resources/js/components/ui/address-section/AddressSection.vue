<template>
    <Card>
        <CardHeader>
            <CardTitle class="text-lg">{{ title }}</CardTitle>
        </CardHeader>
        <CardContent>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                {{ address }}
            </p>
            <div v-if="showMap" class="space-y-3">
                <div class="h-48 bg-gray-100 rounded-lg overflow-hidden relative border">
                    <iframe
                        v-if="mapUrl"
                        :src="mapUrl"
                        class="w-full h-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Map Preview"
                    ></iframe>
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-500">
                        <p class="text-sm">Unable to load map preview</p>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <Button 
                        variant="outline" 
                        size="sm" 
                        @click="openInGoogleMaps"
                        class="flex-1"
                    >
                        Open in Google Maps
                    </Button>
                    <Button 
                        variant="outline" 
                        size="sm" 
                        @click="openInAppleMaps"
                        class="flex-1"
                    >
                        Open in Apple Maps
                    </Button>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';

interface Props {
    title?: string;
    address: string;
    showMap?: boolean;
    mapButtonText?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Address',
    showMap: true,
    mapButtonText: 'View map'
});

const mapUrl = computed(() => {
    if (!props.address) return null;
    
    const encodedAddress = encodeURIComponent(props.address);
    
    return `https://www.openstreetmap.org/export/embed.html?bbox=&layer=mapnik&marker=&q=${encodedAddress}`;
});

const openInGoogleMaps = () => {
    const encodedAddress = encodeURIComponent(props.address);
    const url = `https://www.google.com/maps/search/?api=1&query=${encodedAddress}`;
    window.open(url, '_blank');
};

const openInAppleMaps = () => {
    const encodedAddress = encodeURIComponent(props.address);
    const url = `http://maps.apple.com/?q=${encodedAddress}`;
    window.open(url, '_blank');
};

defineEmits<{
    viewMap: [];
}>();
</script>
