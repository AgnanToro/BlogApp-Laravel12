<template>
  <nav class="w-full">
    <ul v-if="totalPages > 1" class="pagination custom-pagination flex flex-wrap items-center justify-center gap-2 md:gap-3 my-4">
      <!-- Previous Page Link -->
      <li :class="['page-item', { disabled: isFirstPage }]" aria-disabled="isFirstPage">
        <button
          class="page-link px-4 py-2 border rounded-lg bg-white text-gray-400"
          :disabled="isFirstPage"
          @click="goToPage(currentPage - 1)"
        >&lt; Back</button>
      </li>

      <!-- First page and leading ellipsis -->
      <li v-if="startPage > 1" class="page-item">
        <button class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" @click="goToPage(1)">1</button>
      </li>
      <li v-if="startPage > 2" class="page-item disabled">
        <span class="page-link bg-white border-0">...</span>
      </li>

      <!-- Page numbers -->
      <li v-for="i in pageNumbers" :key="i" :class="['page-item', { active: i === currentPage }]" :aria-current="i === currentPage ? 'page' : null">
        <span v-if="i === currentPage" class="page-link px-4 py-2 border rounded-lg bg-blue-600 text-white font-bold shadow">{{ i }}</span>
        <button v-else class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" @click="goToPage(i)">{{ i }}</button>
      </li>

      <!-- Trailing ellipsis and last page -->
      <li v-if="endPage < totalPages - 1" class="page-item disabled">
        <span class="page-link bg-white border-0">...</span>
      </li>
      <li v-if="endPage < totalPages" class="page-item">
        <button class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" @click="goToPage(totalPages)">{{ totalPages }}</button>
      </li>

      <!-- Next Page Link -->
      <li :class="['page-item', { disabled: isLastPage }]" aria-disabled="isLastPage">
        <button
          class="page-link px-4 py-2 border rounded-lg bg-white text-gray-400"
          :disabled="isLastPage"
          @click="goToPage(currentPage + 1)"
        >Next &gt;</button>
      </li>
    </ul>

  </nav>
</template>

<script setup>
import { computed, toRefs } from 'vue'

const props = defineProps({
  currentPage: { type: Number, required: true },
  totalPages: { type: Number, required: true },
  max: { type: Number, default: 5 },
})
const emit = defineEmits(['page-change'])

const { currentPage, totalPages, max } = toRefs(props)

const isFirstPage = computed(() => currentPage.value === 1)
const isLastPage = computed(() => currentPage.value === totalPages.value)

const half = computed(() => Math.floor(max.value / 2))
const startPage = computed(() => {
  let start = Math.max(1, currentPage.value - half.value)
  let end = Math.min(totalPages.value, start + max.value - 1)
  if (end - start < max.value - 1) {
    start = Math.max(1, end - max.value + 1)
  }
  return start
})
const endPage = computed(() => {
  let end = Math.min(totalPages.value, startPage.value + max.value - 1)
  return end
})
const pageNumbers = computed(() => {
  const pages = []
  for (let i = startPage.value; i <= endPage.value; i++) {
    pages.push(i)
  }
  return pages
})

function goToPage(page) {
  if (page < 1 || page > totalPages.value || page === currentPage.value) return
  emit('page-change', page)
}
</script>

<style scoped>
.custom-pagination .page-link {
  border: none;
  border-radius: 0.75rem;
  background: #fff;
  color: #222;
  font-weight: 500;
  min-width: 2.5rem;
  min-height: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: none;
  transition: background 0.2s, color 0.2s;
}
.custom-pagination .page-item.active .page-link {
  background: #2563eb;
  color: #fff;
  font-weight: bold;
}
.custom-pagination .page-item.disabled .page-link {
  background: #f5f5f5;
  color: #bbb;
}
.custom-pagination .page-link:hover:not(:disabled) {
  background: #2563eb;
  color: #fff;
}
</style>
