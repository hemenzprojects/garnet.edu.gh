<template>
  <div class="page-builder-content">
    <!-- Basic Blocks -->
    <template v-for="(block, index) in sortedBlocks" :key="block.id || `block-${index}`">
      <PageBuilderTextBlock v-if="block.type === 'text_block'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderHeading v-else-if="block.type === 'heading'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderImage v-else-if="block.type === 'image'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderButton v-else-if="block.type === 'button'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderSpacer v-else-if="block.type === 'spacer'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderDivider v-else-if="block.type === 'divider'" :data="block.data" :block-id="block.id || `block-${index}`" />

      <!-- Section Blocks -->
      <PageBuilderHero v-else-if="block.type === 'hero'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderStats v-else-if="block.type === 'stats'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderTwoColumn v-else-if="block.type === 'two_column'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderCardGrid v-else-if="block.type === 'card_grid'" :data="block.data" :block-id="block.id || `block-${index}`" />

      <!-- Form Blocks -->
      <PageBuilderContactForm v-else-if="block.type === 'contact_form'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderSearchBox v-else-if="block.type === 'search_box'" :data="block.data" :block-id="block.id || `block-${index}`" />

      <!-- Dynamic Blocks -->
      <PageBuilderDynamicNews v-else-if="block.type === 'dynamic_news'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderDynamicEvents v-else-if="block.type === 'dynamic_events'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderDynamicServices v-else-if="block.type === 'dynamic_services'" :data="block.data" :block-id="block.id || `block-${index}`" />
      <PageBuilderDynamicMembers v-else-if="block.type === 'dynamic_members'" :data="block.data" :block-id="block.id || `block-${index}`" />

      <!-- Unknown Block -->
      <PageBuilderUnknown v-else :data="block.data" :block-id="block.id || `block-${index}`" />
    </template>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  blocks: any[]
}>()

const { sortBlocks } = usePageBuilder()

const sortedBlocks = computed(() => {
  // Filter out section metadata blocks (used only by Elementor editor)
  const filteredBlocks = (props.blocks || []).filter(block => block.type !== '_section_meta')
  return sortBlocks(filteredBlocks)
})
</script>
