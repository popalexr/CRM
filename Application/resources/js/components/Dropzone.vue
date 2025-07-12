<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch, type PropType } from 'vue'
import Dropzone from 'dropzone'
import 'dropzone/dist/dropzone.css'

/* --------------------- props & events --------------------- */
const props = defineProps({
  url: { type: String, required: true },

  acceptedFiles: { type: String, default: '' },
  maxFiles: { type: Number as PropType<number | null>, default: null },
  maxFilesize: { type: Number, default: 256 },          // MB
  headers: { type: Object as PropType<Record<string, string>>, default: () => ({}) },
  params:  { type: Object as PropType<Record<string, any>>, default: () => ({}) },
  clickable: { type: Boolean, default: true },
  autoProcessQueue: { type: Boolean, default: true },
  customClass: { type: String, default: '' }
})

const emit = defineEmits<{
  (e: 'added', file: File): void
  (e: 'success', payload: { response: any }): void
  (e: 'error',   payload: { errorMessage: string | any }): void
  (e: 'removed', file: File): void
  (e: 'queue-complete'): void
  (e: 'sending', payload: { file: File; xhr: XMLHttpRequest; formData: FormData }): void
}>()

/* --------------------- instance --------------------------- */
const dropzoneEl = ref<HTMLElement | null>(null)
let dz: Dropzone | null = null

onMounted(() => {
  dz = new Dropzone(dropzoneEl.value as HTMLElement, {
    url: props.url,
    acceptedFiles: props.acceptedFiles,
    maxFiles: props.maxFiles ?? 1,
    maxFilesize: props.maxFilesize,
    headers: props.headers,
    params: props.params,
    clickable: props.clickable,
    autoProcessQueue: props.autoProcessQueue,
    addRemoveLinks: true,
    chunking: true,
    chunkSize: 1000000,  // 1 MB in bytes
    parallelChunkUploads: false,
    retryChunks: true,
    retryChunksLimit: 3,

    init () {
      this.on('addedfile',  file        => emit('added', file))
      this.on('success',    (f, res)   => emit('success', { response: res }))
      this.on('error',      (f, msg)   => emit('error', { errorMessage: msg }))
      this.on('removedfile',file        => emit('removed', file))
      this.on('queuecomplete',          () => emit('queue-complete'))
      this.on('sending',    (f, xhr,fd) => emit('sending', { file: f, xhr, formData: fd }))
    }
  })
})

watch(() => props.url, url => { if (dz) dz.options.url = url })

onBeforeUnmount(() => { dz?.destroy?.(); dz = null })
</script>

<template>
  <div
    ref="dropzoneEl"
    class="dropzone dz-clickable"
    :class="customClass"
  >
  </div>
</template>