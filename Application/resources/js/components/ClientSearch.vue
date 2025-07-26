<template>
  <div class="relative w-full">
    <Input
      ref="inputRef"
      v-model="selectedName"
      @input="onInput"
      @focus="focused = true"
      @blur="onBlur"
      :placeholder="placeholder || 'Search clients...'"
      class="w-full"
    />

    <div
      v-if="focused"
      class="absolute z-10 mt-1 w-full bg-popover shadow-popover rounded-md max-h-60 overflow-auto border border-border"
    >
      <ul>
        <li
          v-for="item in suggestions"
          :key="item.id"
          @mousedown.prevent="select(item)"
          class="px-4 py-2 hover:bg-accent hover:text-accent-foreground cursor-pointer"
        >
          {{ item.client_name }}
        </li>
      </ul>
      <div v-if="!suggestions.length" class="p-4 text-sm text-muted-foreground">
        Client not found.
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, nextTick } from 'vue'
import axios from 'axios'
import { Input } from '@/components/ui/input'

interface Client {
  id: string
  client_name: string
}

const props = defineProps<{
  fetchUrl: string
  placeholder?: string

  modelValue?: string | number | null
  defaultValue?: string | null
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
  (e: 'select', item: Client): void
  (e: 'value-changed', value: string | number): void
}>()

const selectedName = ref<string>(props.defaultValue ? String(props.defaultValue) : '')
const suggestions = ref<Client[]>([])
const focused = ref(false)
const inputRef = ref<any>(null)

async function onInput() {
  const query = selectedName.value.trim()

  try {
    const { data } = await axios.get<Client[]>(props.fetchUrl, {
      params: { q: query }
    })
    suggestions.value = data

    if (
      suggestions.value.length === 1 &&
      suggestions.value[0].client_name === query
    ) {
      select(suggestions.value[0])
      return
    }
  } catch {
    suggestions.value = []
  }

  emit('value-changed', query)
}

function select(item: Client) {
  selectedName.value = item.client_name
  emit('update:modelValue', item.id)
  emit('select', item)
  focused.value = false

  // Blur the underlying input element
  nextTick(() => {
    const component = inputRef.value as any
    const el = component?.$el?.querySelector('input')
    el?.blur()
  })
}

function onBlur() {
  setTimeout(() => { focused.value = false }, 100)
}
</script>
