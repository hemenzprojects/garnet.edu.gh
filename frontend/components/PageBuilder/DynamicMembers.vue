<template>
  <section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 v-if="data.heading" class="text-4xl font-bold text-gray-900 text-center mb-12">
        {{ data.heading }}
      </h2>

      <div v-if="data.display === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <NuxtLink
          v-for="member in data.items"
          :key="member.id"
          :to="`/members/${member.slug}`"
          class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 group"
        >
          <div v-if="member.logo" class="aspect-square w-full mb-4 flex items-center justify-center bg-gray-50 rounded-lg overflow-hidden">
            <img
              :src="getImageUrl(member.logo)"
              :alt="member.name"
              class="max-w-full max-h-full object-contain p-4"
              loading="lazy"
            />
          </div>
          <h3 class="text-center font-semibold text-gray-900 group-hover:text-primary transition">
            {{ member.name }}
          </h3>
          <p v-if="member.type" class="text-center text-sm text-gray-500 mt-1 capitalize">
            {{ member.type.replace('_', ' ') }}
          </p>
        </NuxtLink>
      </div>

      <div v-else-if="data.display === 'logos'" class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        <a
          v-for="member in data.items"
          :key="member.id"
          :href="member.website"
          target="_blank"
          rel="noopener noreferrer"
          class="aspect-square bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 p-4 flex items-center justify-center group"
          :title="member.name"
        >
          <img
            v-if="member.logo"
            :src="getImageUrl(member.logo)"
            :alt="member.name"
            class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform"
            loading="lazy"
          />
        </a>
      </div>

      <div v-else-if="data.display === 'list'" class="max-w-4xl mx-auto space-y-6">
        <NuxtLink
          v-for="member in data.items"
          :key="member.id"
          :to="`/members/${member.slug}`"
          class="flex gap-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 group"
        >
          <div v-if="member.logo" class="w-24 h-24 flex-shrink-0 bg-gray-50 rounded-lg flex items-center justify-center p-3">
            <img
              :src="getImageUrl(member.logo)"
              :alt="member.name"
              class="max-w-full max-h-full object-contain"
              loading="lazy"
            />
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-primary transition">
              {{ member.name }}
            </h3>
            <p v-if="member.type" class="text-sm text-gray-500 mb-2 capitalize">
              {{ member.type.replace('_', ' ') }}
            </p>
            <p v-if="member.description" class="text-gray-600">
              {{ truncate(member.description, 150) }}
            </p>
            <a
              v-if="member.website"
              :href="member.website"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1 text-accent hover:underline mt-2"
              @click.stop
            >
              <span>Visit website</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
              </svg>
            </a>
          </div>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data: {
    heading?: string
    items: any[]
    display: 'grid' | 'logos' | 'list'
    limit: string | number
  }
  blockId: string
}>()

const { getImageUrl } = useImageUrl()

const truncate = (text: string, length: number) => {
  if (!text) return ''
  if (text.length <= length) return text
  return text.substring(0, length) + '...'
}
</script>
