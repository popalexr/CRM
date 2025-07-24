import { onMounted, onBeforeUnmount, Ref } from 'vue'

export function useClickOutside(
  targetRef: Ref<HTMLElement | null>,
  callback: () => void
) {
  function onClick(e: MouseEvent) {
    const el = targetRef.value
    if (el && !el.contains(e.target as Node)) {
      callback()
    }
  }

  onMounted(() => {
    document.addEventListener('mousedown', onClick)
  })
  onBeforeUnmount(() => {
    document.removeEventListener('mousedown', onClick)
  })
}