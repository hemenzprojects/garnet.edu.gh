<template>
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center space-x-2">
          <div v-if="logoUrl" class="h-12 flex items-center">
            <img :src="logoUrl" :alt="siteName" class="h-full w-auto object-contain" />
          </div>
          <div v-else class="w-10 h-10 bg-gradient-to-br from-primary to-accent rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-xl">{{ siteName.charAt(0) }}</span>
          </div>
          <span class="text-2xl font-bold text-gray-900">{{ siteName }}<span class="text-accent">.</span></span>
        </NuxtLink>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <NuxtLink to="/" class="text-gray-700 hover:text-primary transition">Home</NuxtLink>
          <NuxtLink to="/about" class="text-gray-700 hover:text-primary transition">About</NuxtLink>
          <NuxtLink to="/services" class="text-gray-700 hover:text-primary transition">Services</NuxtLink>
          <NuxtLink to="/news" class="text-gray-700 hover:text-primary transition">News</NuxtLink>
          <NuxtLink to="/members" class="text-gray-700 hover:text-primary transition">Members</NuxtLink>
          <NuxtLink to="/contact" class="text-gray-700 hover:text-primary transition">Contact</NuxtLink>
        </div>

        <!-- Mobile Menu Button -->
        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              v-if="!mobileMenuOpen"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
            <path
              v-else
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div
        v-if="mobileMenuOpen"
        class="md:hidden mt-4 py-4 border-t border-gray-200"
      >
        <div class="flex flex-col space-y-4">
          <NuxtLink to="/" class="text-gray-700 hover:text-primary transition">Home</NuxtLink>
          <NuxtLink to="/about" class="text-gray-700 hover:text-primary transition">About</NuxtLink>
          <NuxtLink to="/services" class="text-gray-700 hover:text-primary transition">Services</NuxtLink>
          <NuxtLink to="/news" class="text-gray-700 hover:text-primary transition">News</NuxtLink>
          <NuxtLink to="/members" class="text-gray-700 hover:text-primary transition">Members</NuxtLink>
          <NuxtLink to="/contact" class="text-gray-700 hover:text-primary transition">Contact</NuxtLink>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
const props = defineProps({
  branding: {
    type: Object,
    default: null
  }
})

const config = useRuntimeConfig()
const mobileMenuOpen = ref(false)

// Computed properties for branding
const siteName = computed(() => props.branding?.site_name || 'GARNET')
const logoUrl = computed(() => {
  if (!props.branding?.logo) return ''

  // Handle full URLs
  if (props.branding.logo.startsWith('http://') || props.branding.logo.startsWith('https://')) {
    return props.branding.logo
  }

  // Construct storage URL using the public backend URL
  const backendUrl = config.public.backendUrl || config.public.apiBase.replace('/api/v1', '')
  const cleanPath = props.branding.logo.startsWith('/') ? props.branding.logo.substring(1) : props.branding.logo
  return `${backendUrl}/storage/${cleanPath}`
})
</script>