<template>
  <div>
    <!-- Hero Section -->
    <HeroSection />

    <!-- Stats Section -->
    <StatsSection />

    <!-- Services Section -->
    <section class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Delivering cutting-edge connectivity and digital solutions for Ghana's academic community
          </p>
        </div>

        <div v-if="servicesPending" class="text-center py-8">
          <div class="animate-pulse text-gray-400">Loading services...</div>
        </div>

        <div v-else-if="servicesError" class="text-center py-8">
          <p class="text-red-600">Error loading services: {{ servicesError.message }}</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="service in services"
            :key="service.id"
            class="group p-8 border border-gray-200 rounded-2xl hover:border-accent hover:shadow-xl transition-all duration-300"
          >
            <div class="w-12 h-12 bg-gradient-to-br from-primary to-accent rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition">{{ service.name }}</h3>
            <p class="text-gray-600 leading-relaxed">{{ service.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-20 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest News</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Stay updated with the latest developments in Ghana's academic network
          </p>
        </div>

        <div v-if="newsPending" class="text-center py-8">
          <div class="animate-pulse text-gray-400">Loading news...</div>
        </div>

        <div v-else-if="newsError" class="text-center py-8">
          <p class="text-red-600">Error loading news: {{ newsError.message }}</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="item in news"
            :key="item.id"
            class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group"
          >
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span>{{ new Date(item.published_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) }}</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition">{{ item.title }}</h3>
            <p class="text-gray-600 mb-6 leading-relaxed">{{ item.excerpt }}</p>
            <NuxtLink
              :to="`/news/${item.slug}`"
              class="inline-flex items-center gap-2 text-accent hover:gap-3 transition-all font-semibold"
            >
              <span>Read more</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const { fetchServices, fetchNews } = useApi()

// Fetch services from backend
const { data: services, pending: servicesPending, error: servicesError } = await useAsyncData(
  'services',
  () => fetchServices()
)

// Fetch latest news from backend
const { data: newsData, pending: newsPending, error: newsError } = await useAsyncData(
  'news',
  () => fetchNews({ limit: 3 })
)

const news = computed(() => newsData.value?.data || [])

useSeoMeta({
  title: 'GARNET - Ghanaian Academic and Research Network',
  description: 'Connecting Ghana\'s education sector through high-speed internet and innovation'
})
</script>