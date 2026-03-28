<template>
  <div class="page-builder-content">
    <template v-for="(section, sectionIndex) in groupedSections" :key="section.id || `section-${sectionIndex}`">
      <!-- Section Wrapper with Background -->
      <div :style="getSectionStyle(section.settings)" class="elementor-section">
        <template v-for="(block, blockIndex) in section.blocks" :key="block.id || `block-${blockIndex}`">
          <!-- Basic Blocks -->
          <PageBuilderTextBlock v-if="block.type === 'text_block'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderHeading v-else-if="block.type === 'heading'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderImage v-else-if="block.type === 'image'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderButton v-else-if="block.type === 'button'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderSpacer v-else-if="block.type === 'spacer'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderDivider v-else-if="block.type === 'divider'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />

          <!-- Section Blocks -->
          <PageBuilderHero v-else-if="block.type === 'hero'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderHeroSplit v-else-if="block.type === 'hero_split'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderStats v-else-if="block.type === 'stats'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderTwoColumn v-else-if="block.type === 'two_column'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderCardGrid v-else-if="block.type === 'card_grid'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderIconCards v-else-if="block.type === 'icon_cards'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderWhoWeAre v-else-if="block.type === 'who_we_are'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderTimeline v-else-if="block.type === 'timeline'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />

          <!-- Form Blocks -->
          <PageBuilderContactForm v-else-if="block.type === 'contact_form'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderSearchBox v-else-if="block.type === 'search_box'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />

          <!-- Dynamic Blocks -->
          <PageBuilderDynamicNews v-else-if="block.type === 'dynamic_news'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderDynamicEvents v-else-if="block.type === 'dynamic_events'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderDynamicServices v-else-if="block.type === 'dynamic_services'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderDynamicMembers v-else-if="block.type === 'dynamic_members'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
          <PageBuilderDynamicTeamMembers v-else-if="block.type === 'dynamic_team_members'" :data="block.data" :block-id="block.id || `block-${blockIndex}`" />

          <!-- Unknown Block -->
          <PageBuilderUnknown v-else :data="block.data" :block-id="block.id || `block-${blockIndex}`" />
        </template>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  blocks: any[]
}>()

const { sortBlocks } = usePageBuilder()

const groupedSections = computed(() => {
  const blocks = sortBlocks(props.blocks || [])
  const sections: any[] = []
  let currentSection: any = null

  blocks.forEach((block) => {
    if (block.type === '_section_meta') {
      // Start a new section
      currentSection = {
        id: block.id,
        settings: block.data?.settings || {},
        blocks: []
      }
      sections.push(currentSection)
    } else {
      // Add block to current section or create default section
      if (!currentSection) {
        currentSection = {
          id: 'default',
          settings: {},
          blocks: []
        }
        sections.push(currentSection)
      }
      currentSection.blocks.push(block)
    }
  })

  return sections
})

const getSectionStyle = (settings: any) => {
  const style: any = {}

  if (settings.backgroundColor) {
    style.backgroundColor = settings.backgroundColor
  }

  if (settings.backgroundImage) {
    style.backgroundImage = `url(${settings.backgroundImage})`
    style.backgroundSize = 'cover'
    style.backgroundPosition = 'center'
  }

  if (settings.padding) {
    style.paddingTop = `${settings.padding.top}px`
    style.paddingRight = `${settings.padding.right}px`
    style.paddingBottom = `${settings.padding.bottom}px`
    style.paddingLeft = `${settings.padding.left}px`
  }

  if (settings.margin) {
    style.marginTop = `${settings.margin.top}px`
    style.marginBottom = `${settings.margin.bottom}px`
  }

  return style
}
</script>
