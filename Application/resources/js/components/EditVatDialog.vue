<script setup lang="ts">
import { ref, watch } from 'vue'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm } from '@inertiajs/vue3'
import InputError from './InputError.vue'

interface Props {
    modelValue: boolean
    title?: string
    vatName?: string
    vatRate?: number | string
    vatId?: number | string
};

const props = defineProps<Props>()
const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void
    (e: 'save', value: { id: string, name: string, rate: number }): void
}>()

// local reactive state
const open = ref(props.modelValue)

const form = useForm({
    id: props.vatId ?? '',
    name: props.vatName ?? '',
    rate: props.vatRate ?? 0,
});

watch(
    () => props.modelValue,
    v => {
        open.value = v;

        if (v) {
            form.id = props.vatId ?? '';
            form.name = props.vatName ?? '';
            form.rate = props.vatRate ?? 0;
        }
        else {
            form.id = '';
            form.name = '';
            form.rate = 0;
        }
    },
)
watch(open, v => emit('update:modelValue', v))

function onSave() {
    form.post(route('settings.vat.update'), {
        onSuccess: (response) => {
            open.value = false; // close dialog

            emit('save', {
                id: form.id,
                name: form.name,
                rate: form.rate,
            });
        },
    });
}
</script>

<template>
  <Dialog v-model:open="open">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ title ?? 'Edit values' }}</DialogTitle>
      </DialogHeader>

      <div class="grid gap-4 py-4">
        <div class="grid gap-2">
            <div class="flex justify-between">    
                <Label for="editVatRateName">VAT Name</Label>
                <InputError :message="form.errors.name"/>
            </div>
            <Input
                id="editVatRateName"
                v-model="form.name"
                placeholder="Name of the VAT rate"
                class="w-full"
            />
        </div>

        <div class="grid gap-2">
            <div class="flex justify-between">
                <Label for="editVatRateValue">VAT Rate</Label>
                <InputError :message="form.errors.rate" />
            </div>
            <Input
                id="editVatRateValue"
                type="number"
                v-model="form.rate"
                placeholder="0.00"
                class="w-full"
            />
        </div>
      </div>

      <DialogFooter>
        <Button @click="onSave">Save</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
