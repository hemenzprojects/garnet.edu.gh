
<template>
  <section :class="heightClass" class="relative overflow-hidden">
    <!-- Background decorative elements -->
    <div v-if="data.showDecorations" class="absolute inset-0 opacity-5">
      <div class="absolute top-0 right-0 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-6 lg:px-12 relative z-10 h-full flex items-center">
      <div class="grid md:grid-cols-2 gap-12 lg:gap-16 items-center w-full">
        <!-- Left Content -->
        <div class="space-y-6">
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
            <template v-if="data.headingLine1">
              <span :class="headingLine1ColorClass">{{ data.headingLine1 }}</span>
              <br />
            </template>
            <template v-if="data.headingLine2">
              <span :class="headingLine2ColorClass">{{ data.headingLine2 }}</span>
              <br />
            </template>
            <template v-if="data.headingLine3">
              <span :class="headingLine3ColorClass">{{ data.headingLine3 }}</span>
            </template>
          </h1>

          <p v-if="data.subheading" class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-xl">
            {{ data.subheading }}
          </p>

          <div class="flex flex-wrap gap-4 pt-4">
            <NuxtLink
              v-if="data.primaryCtaText && data.primaryCtaLink"
              :to="data.primaryCtaLink"
              class="px-8 py-3 bg-primary text-white font-semibold rounded-md hover:bg-primary-dark transition-colors duration-300 shadow-lg hover:shadow-xl"
            >
              {{ data.primaryCtaText }}
            </NuxtLink>

            <NuxtLink
              v-if="data.secondaryCtaText && data.secondaryCtaLink"
              :to="data.secondaryCtaLink"
              class="px-8 py-3 bg-gray-200 text-gray-800 font-semibold rounded-md hover:bg-gray-300 transition-colors duration-300"
            >
              {{ data.secondaryCtaText }}
            </NuxtLink>
          </div>
        </div>

        <!-- Right Content -->
        <div class="relative">
          <div
            :class="[
              'rounded-2xl p-12 md:p-16 shadow-2xl min-h-[400px] flex items-center justify-center',
              featureBoxColorClass
            ]"
          >
            <div class="text-center space-y-8">
              <div class="space-y-4">
                <!-- Top Divider with Text -->
                <div v-if="data.featureBoxTopText" class="flex items-center justify-center gap-4">
                  <div class="h-px bg-white/50 flex-1"></div>
                  <span class="text-white/80 font-light tracking-widest text-sm">
                    {{ data.featureBoxTopText }}
                  </span>
                  <div class="h-px bg-white/50 flex-1"></div>
                </div>

                <!-- Main Feature Text -->
                <h2 v-if="data.featureBoxMainText" class="text-4xl md:text-5xl font-bold text-white tracking-wide">
                  {{ data.featureBoxMainText }}
                </h2>

                <!-- Bottom Feature Text -->
                <h3 v-if="data.featureBoxBottomText" class="text-3xl md:text-4xl font-bold text-white tracking-wider">
                  {{ data.featureBoxBottomText }}
                </h3>
              </div>
            </div>
          </div>

          <!-- Decorative corner accent -->
          <div v-if="data.showDecorations" class="absolute -bottom-4 -right-4 w-24 h-24 bg-purple-600 rounded-lg opacity-20 blur-xl"></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data: {
    // Heading Configuration
    headingLine1?: string
    headingLine1Color?: 'primary' | 'purple' | 'accent' | 'secondary' | 'gray'
    headingLine2?: string
    headingLine2Color?: 'primary' | 'purple' | 'accent' | 'secondary' | 'gray'
    headingLine3?: string
    headingLine3Color?: 'primary' | 'purple' | 'accent' | 'secondary' | 'gray'
    subheading?: string

    // Call to Action
    primaryCtaText?: string
    primaryCtaLink?: string
    secondaryCtaText?: string
    secondaryCtaLink?: string

    // Feature Box
    featureBoxTopText?: string
    featureBoxMainText?: string
    featureBoxBottomText?: string
    featureBoxColor?: 'teal' | 'primary' | 'purple' | 'accent' | 'secondary'

    // Layout Options
    height: 'medium' | 'large' | 'full'
    showDecorations: boolean
  }
  blockId: string
}>()

const heightClass = computed(() => {
  const heightMap: Record<string, string> = {
    medium: 'min-h-[600px] py-16 md:py-20',
    large: 'min-h-[700px] py-16 md:py-24',
    full: 'min-h-screen py-16 md:py-24',
  }
  return heightMap[props.data.height] || heightMap.large
})

const getColorClass = (color?: string, defaultColor: string = 'primary') => {
  const colorMap: Record<string, string> = {
    primary: 'text-primary',
    purple: 'text-purple-600',
    accent: 'text-accent',
    secondary: 'text-secondary',
    gray: 'text-gray-800',
  }
  return colorMap[color || defaultColor] || colorMap[defaultColor]
}

const headingLine1ColorClass = computed(() => getColorClass(props.data.headingLine1Color, 'primary'))
const headingLine2ColorClass = computed(() => getColorClass(props.data.headingLine2Color, 'purple'))
const headingLine3ColorClass = computed(() => getColorClass(props.data.headingLine3Color, 'primary'))

const featureBoxColorClass = computed(() => {
  const colorMap: Record<string, string> = {
    teal: 'bg-gradient-to-br from-teal-600 to-teal-700',
    primary: 'bg-gradient-to-br from-primary to-primary-dark',
    purple: 'bg-gradient-to-br from-purple-600 to-purple-800',
    accent: 'bg-gradient-to-br from-accent to-accent-dark',
    secondary: 'bg-gradient-to-br from-secondary to-emerald-700',
  }
  return colorMap[props.data.featureBoxColor || 'teal'] || colorMap.teal
})
</script>

<style scoped>
/* Additional custom styles if needed */
</style>