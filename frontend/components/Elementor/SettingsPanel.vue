<template>
  <div class="w-80 bg-white border-l border-gray-200 flex flex-col h-full overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
      <div>
        <h3 class="text-sm font-semibold text-gray-900">{{ elementTitle }}</h3>
        <p class="text-xs text-gray-500">{{ elementSubtitle }}</p>
      </div>
      <button
        @click="$emit('close')"
        class="text-gray-400 hover:text-gray-600 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 bg-white">
      <button
        v-for="tab in availableTabs"
        :key="tab"
        @click="activeTab = tab"
        :class="[
          'flex-1 px-4 py-2 text-sm font-medium border-b-2 transition-colors',
          activeTab === tab
            ? 'text-blue-600 border-blue-600'
            : 'text-gray-600 border-transparent hover:text-gray-900'
        ]"
      >
        {{ tab }}
      </button>
    </div>

    <!-- Settings Content -->
    <div class="flex-1 overflow-y-auto p-4">
      <!-- Section Settings -->
      <div v-if="selectedElement.type === 'section'" class="space-y-4">
        <!-- Content Tab -->
        <div v-if="activeTab === 'Content'">
          <div class="space-y-4">
            <!-- Layout -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Layout</label>
              <select
                :value="elementData.layout"
                @change="updateSetting('layout', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="boxed">Boxed</option>
                <option value="full_width">Full Width</option>
              </select>
            </div>

            <!-- Column Gap -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Column Gap</label>
              <select
                :value="elementData.gap"
                @change="updateSetting('gap', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="no">No Gap</option>
                <option value="narrow">Narrow</option>
                <option value="default">Default</option>
                <option value="wide">Wide</option>
              </select>
            </div>

            <!-- Height -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Height</label>
              <select
                :value="elementData.height"
                @change="updateSetting('height', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="default">Default</option>
                <option value="min-height">Min Height</option>
                <option value="fit-to-screen">Fit to Screen</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Style Tab -->
        <div v-else-if="activeTab === 'Style'">
          <div class="space-y-4">
            <!-- Background Color -->
            <ElementorColorPicker
              :model-value="elementData.backgroundColor || '#FFFFFF'"
              @update:model-value="updateSetting('backgroundColor', $event)"
              label="Background Color"
            />

            <!-- Background Image -->
            <ElementorImageUpload
              :model-value="elementData.backgroundImage || ''"
              @update:model-value="updateSetting('backgroundImage', $event)"
              label="Background Image"
              type="hero"
            />

            <!-- Padding -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Padding (px)</label>
              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Top</label>
                  <input
                    type="number"
                    :value="elementData.padding?.top || 0"
                    @input="updatePadding('top', ($event.target as HTMLInputElement).value)"
                    min="0"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Right</label>
                  <input
                    type="number"
                    :value="elementData.padding?.right || 0"
                    @input="updatePadding('right', ($event.target as HTMLInputElement).value)"
                    min="0"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Bottom</label>
                  <input
                    type="number"
                    :value="elementData.padding?.bottom || 0"
                    @input="updatePadding('bottom', ($event.target as HTMLInputElement).value)"
                    min="0"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Left</label>
                  <input
                    type="number"
                    :value="elementData.padding?.left || 0"
                    @input="updatePadding('left', ($event.target as HTMLInputElement).value)"
                    min="0"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>
            </div>

            <!-- Margin -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Margin (px)</label>
              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Top</label>
                  <input
                    type="number"
                    :value="elementData.margin?.top || 0"
                    @input="updateMargin('top', ($event.target as HTMLInputElement).value)"
                    min="0"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Bottom</label>
                  <input
                    type="number"
                    :value="elementData.margin?.bottom || 0"
                    @input="updateMargin('bottom', ($event.target as HTMLInputElement).value)"
                    min="0"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Widget Settings -->
      <div v-else-if="selectedElement.type === 'widget'" class="space-y-4">
        <!-- Heading Widget -->
        <div v-if="widgetType === 'heading'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Text</label>
              <input
                type="text"
                :value="elementData.text"
                @input="updateData('text', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- HTML Tag -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">HTML Tag</label>
              <select
                :value="elementData.tag || 'h2'"
                @change="updateData('tag', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="h1">H1</option>
                <option value="h2">H2</option>
                <option value="h3">H3</option>
                <option value="h4">H4</option>
                <option value="h5">H5</option>
                <option value="h6">H6</option>
              </select>
            </div>

            <!-- Alignment -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Alignment</label>
              <div class="flex space-x-2">
                <button
                  v-for="align in ['left', 'center', 'right']"
                  :key="align"
                  @click="updateData('alignment', align)"
                  :class="[
                    'flex-1 px-3 py-2 rounded border transition-colors',
                    elementData.alignment === align
                      ? 'bg-blue-600 text-white border-blue-600'
                      : 'bg-white text-gray-700 border-gray-300 hover:border-blue-500'
                  ]"
                >
                  {{ align.charAt(0).toUpperCase() + align.slice(1) }}
                </button>
              </div>
            </div>
          </div>

          <div v-else-if="activeTab === 'Style'" class="space-y-4">
            <!-- Color -->
            <ElementorColorPicker
              :model-value="elementData.color || '#000000'"
              @update:model-value="updateData('color', $event)"
              label="Color"
            />

            <!-- Font Size -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Font Size (px)</label>
              <input
                type="number"
                :value="elementData.typography?.size || 32"
                @input="updateTypography('size', parseInt(($event.target as HTMLInputElement).value))"
                min="8"
                max="120"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Font Weight -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Font Weight</label>
              <select
                :value="elementData.typography?.weight || 600"
                @change="updateTypography('weight', parseInt(($event.target as HTMLSelectElement).value))"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option :value="300">Light</option>
                <option :value="400">Normal</option>
                <option :value="500">Medium</option>
                <option :value="600">Semi Bold</option>
                <option :value="700">Bold</option>
                <option :value="800">Extra Bold</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Text Editor Widget -->
        <div v-else-if="widgetType === 'text_editor'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Content -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
              <textarea
                :value="elementData.content"
                @input="updateData('content', ($event.target as HTMLTextAreaElement).value)"
                rows="10"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono"
              ></textarea>
              <p class="text-xs text-gray-500 mt-1">HTML is supported</p>
            </div>

            <!-- Alignment -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Alignment</label>
              <div class="flex space-x-2">
                <button
                  v-for="align in ['left', 'center', 'right', 'justify']"
                  :key="align"
                  @click="updateData('alignment', align)"
                  :class="[
                    'flex-1 px-3 py-2 rounded border transition-colors text-xs',
                    elementData.alignment === align
                      ? 'bg-blue-600 text-white border-blue-600'
                      : 'bg-white text-gray-700 border-gray-300 hover:border-blue-500'
                  ]"
                >
                  {{ align.charAt(0).toUpperCase() + align.slice(1) }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Image Widget -->
        <div v-else-if="widgetType === 'image'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Image URL -->
            <ElementorImageUpload
              :model-value="elementData.url || ''"
              @update:model-value="updateData('url', $event)"
              label="Image"
              type="general"
            />

            <!-- Alt Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
              <input
                type="text"
                :value="elementData.alt"
                @input="updateData('alt', ($event.target as HTMLInputElement).value)"
                placeholder="Image description"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Alignment -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Alignment</label>
              <div class="flex space-x-2">
                <button
                  v-for="align in ['left', 'center', 'right']"
                  :key="align"
                  @click="updateData('alignment', align)"
                  :class="[
                    'flex-1 px-3 py-2 rounded border transition-colors',
                    elementData.alignment === align
                      ? 'bg-blue-600 text-white border-blue-600'
                      : 'bg-white text-gray-700 border-gray-300 hover:border-blue-500'
                  ]"
                >
                  {{ align.charAt(0).toUpperCase() + align.slice(1) }}
                </button>
              </div>
            </div>

            <!-- Width -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Width</label>
              <div class="flex items-center space-x-2">
                <input
                  type="number"
                  :value="elementData.width?.size || 100"
                  @input="updateImageSize('size', parseInt(($event.target as HTMLInputElement).value))"
                  min="1"
                  max="100"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <select
                  :value="elementData.width?.unit || '%'"
                  @change="updateImageSize('unit', ($event.target as HTMLSelectElement).value)"
                  class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="%">%</option>
                  <option value="px">px</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Button Widget -->
        <div v-else-if="widgetType === 'button'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Text</label>
              <input
                type="text"
                :value="elementData.text"
                @input="updateData('text', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- URL -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
              <input
                type="text"
                :value="elementData.url"
                @input="updateData('url', ($event.target as HTMLInputElement).value)"
                placeholder="https://example.com"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Alignment -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Alignment</label>
              <div class="flex space-x-2">
                <button
                  v-for="align in ['left', 'center', 'right']"
                  :key="align"
                  @click="updateData('alignment', align)"
                  :class="[
                    'flex-1 px-3 py-2 rounded border transition-colors',
                    elementData.alignment === align
                      ? 'bg-blue-600 text-white border-blue-600'
                      : 'bg-white text-gray-700 border-gray-300 hover:border-blue-500'
                  ]"
                >
                  {{ align.charAt(0).toUpperCase() + align.slice(1) }}
                </button>
              </div>
            </div>
          </div>

          <div v-else-if="activeTab === 'Style'" class="space-y-4">
            <!-- Button Style -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Button Style</label>
              <select
                :value="elementData.style || 'primary'"
                @change="updateData('style', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="primary">Primary</option>
                <option value="secondary">Secondary</option>
                <option value="accent">Accent</option>
                <option value="outline">Outline</option>
              </select>
            </div>

            <!-- Size -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
              <select
                :value="elementData.size || 'md'"
                @change="updateData('size', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="sm">Small</option>
                <option value="md">Medium</option>
                <option value="lg">Large</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Spacer Widget -->
        <div v-else-if="widgetType === 'spacer'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Height -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Height</label>
              <div class="flex items-center space-x-2">
                <input
                  type="number"
                  :value="elementData.space?.size || 50"
                  @input="updateSpaceSize('size', parseInt(($event.target as HTMLInputElement).value))"
                  min="0"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <select
                  :value="elementData.space?.unit || 'px'"
                  @change="updateSpaceSize('unit', ($event.target as HTMLSelectElement).value)"
                  class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="px">px</option>
                  <option value="em">em</option>
                  <option value="%">%</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Divider Widget -->
        <div v-else-if="widgetType === 'divider'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Style -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Style</label>
              <select
                :value="elementData.style || 'solid'"
                @change="updateData('style', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="solid">Solid</option>
                <option value="dashed">Dashed</option>
                <option value="dotted">Dotted</option>
              </select>
            </div>

            <!-- Color -->
            <ElementorColorPicker
              :model-value="elementData.color || '#E0E0E0'"
              @update:model-value="updateData('color', $event)"
              label="Color"
            />

            <!-- Weight -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Weight</label>
              <div class="flex items-center space-x-2">
                <input
                  type="number"
                  :value="elementData.weight?.size || 1"
                  @input="updateWeightSize('size', parseInt(($event.target as HTMLInputElement).value))"
                  min="1"
                  max="10"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <select
                  :value="elementData.weight?.unit || 'px'"
                  @change="updateWeightSize('unit', ($event.target as HTMLSelectElement).value)"
                  class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="px">px</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Hero Widget -->
        <div v-else-if="widgetType === 'hero'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Subheading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Subheading</label>
              <textarea
                :value="elementData.subheading"
                @input="updateData('subheading', ($event.target as HTMLTextAreaElement).value)"
                rows="2"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <!-- CTA Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
              <input
                type="text"
                :value="elementData.ctaText"
                @input="updateData('ctaText', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- CTA Link -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
              <input
                type="text"
                :value="elementData.ctaLink"
                @input="updateData('ctaLink', ($event.target as HTMLInputElement).value)"
                placeholder="https://example.com"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Background Image -->
            <ElementorImageUpload
              :model-value="elementData.backgroundImage || ''"
              @update:model-value="updateData('backgroundImage', $event)"
              label="Background Image"
              type="hero"
            />

            <!-- Foreground Image -->
            <ElementorImageUpload
              :model-value="elementData.foregroundImage || ''"
              @update:model-value="updateData('foregroundImage', $event)"
              label="Foreground Image"
              type="hero"
            />
          </div>

          <div v-else-if="activeTab === 'Style'" class="space-y-4">
            <!-- Height -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Height</label>
              <select
                :value="elementData.height || 'large'"
                @change="updateData('height', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="medium">Medium</option>
                <option value="large">Large</option>
                <option value="full">Full Screen</option>
              </select>
            </div>

            <!-- Overlay -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Overlay</label>
              <select
                :value="elementData.overlay || 'gradient'"
                @change="updateData('overlay', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="none">None</option>
                <option value="dark">Dark</option>
                <option value="gradient">Gradient</option>
                <option value="primary">Primary Color</option>
              </select>
            </div>

            <!-- Show Particles -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showParticles"
                  @change="updateData('showParticles', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Animated Particles</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Hero Split Widget -->
        <div v-else-if="widgetType === 'hero_split'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading Line 1 -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading Line 1</label>
              <input
                type="text"
                :value="elementData.headingLine1"
                @input="updateData('headingLine1', ($event.target as HTMLInputElement).value)"
                placeholder="Empowering Ghana's"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Heading Line 1 Color -->
            <ElementorColorPicker
              :model-value="elementData.headingLine1Color || '#0A1E3E'"
              @update:model-value="updateData('headingLine1Color', $event)"
              label="Line 1 Color"
            />

            <!-- Heading Line 2 -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading Line 2</label>
              <input
                type="text"
                :value="elementData.headingLine2"
                @input="updateData('headingLine2', ($event.target as HTMLInputElement).value)"
                placeholder="Intellectual"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Heading Line 2 Color -->
            <ElementorColorPicker
              :model-value="elementData.headingLine2Color || '#9333EA'"
              @update:model-value="updateData('headingLine2Color', $event)"
              label="Line 2 Color"
            />

            <!-- Heading Line 3 -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading Line 3</label>
              <input
                type="text"
                :value="elementData.headingLine3"
                @input="updateData('headingLine3', ($event.target as HTMLInputElement).value)"
                placeholder="Backbone"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Heading Line 3 Color -->
            <ElementorColorPicker
              :model-value="elementData.headingLine3Color || '#0A1E3E'"
              @update:model-value="updateData('headingLine3Color', $event)"
              label="Line 3 Color"
            />

            <!-- Subheading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Subheading</label>
              <textarea
                :value="elementData.subheading"
                @input="updateData('subheading', ($event.target as HTMLTextAreaElement).value)"
                rows="3"
                placeholder="Join a prestigious ecosystem..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <!-- Primary CTA Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Primary Button Text</label>
              <input
                type="text"
                :value="elementData.primaryCtaText"
                @input="updateData('primaryCtaText', ($event.target as HTMLInputElement).value)"
                placeholder="Get Started"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Primary CTA Link -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Primary Button Link</label>
              <input
                type="text"
                :value="elementData.primaryCtaLink"
                @input="updateData('primaryCtaLink', ($event.target as HTMLInputElement).value)"
                placeholder="/membership/apply"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Secondary CTA Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Text</label>
              <input
                type="text"
                :value="elementData.secondaryCtaText"
                @input="updateData('secondaryCtaText', ($event.target as HTMLInputElement).value)"
                placeholder="Learn More"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Secondary CTA Link -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Link</label>
              <input
                type="text"
                :value="elementData.secondaryCtaLink"
                @input="updateData('secondaryCtaLink', ($event.target as HTMLInputElement).value)"
                placeholder="/about"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Feature Box Top Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Feature Box Top Text</label>
              <input
                type="text"
                :value="elementData.featureBoxTopText"
                @input="updateData('featureBoxTopText', ($event.target as HTMLInputElement).value)"
                placeholder="MADEMIIC"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Feature Box Main Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Feature Box Main Text</label>
              <input
                type="text"
                :value="elementData.featureBoxMainText"
                @input="updateData('featureBoxMainText', ($event.target as HTMLInputElement).value)"
                placeholder="NAIDMIIC RESEARCH"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Feature Box Bottom Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Feature Box Bottom Text</label>
              <input
                type="text"
                :value="elementData.featureBoxBottomText"
                @input="updateData('featureBoxBottomText', ($event.target as HTMLInputElement).value)"
                placeholder="SAFE WORK"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <div v-else-if="activeTab === 'Style'" class="space-y-4">
            <!-- Feature Box Color -->
            <ElementorColorPicker
              :model-value="elementData.featureBoxColor || '#0D9488'"
              @update:model-value="updateData('featureBoxColor', $event)"
              label="Feature Box Color"
            />

            <!-- Height -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Section Height</label>
              <select
                :value="elementData.height || 'large'"
                @change="updateData('height', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="medium">Medium</option>
                <option value="large">Large</option>
                <option value="full">Full Screen</option>
              </select>
            </div>

            <!-- Show Decorations -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showDecorations"
                  @change="updateData('showDecorations', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Background Decorations</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Stats Widget -->
        <div v-else-if="widgetType === 'stats'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Stats Items -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Stats Items</label>
              <div class="space-y-3">
                <div
                  v-for="(item, index) in elementData.items"
                  :key="index"
                  class="p-3 border border-gray-200 rounded-lg"
                >
                  <div class="grid grid-cols-2 gap-2">
                    <input
                      type="text"
                      :value="item.value"
                      @input="updateStatsItem(index, 'value', ($event.target as HTMLInputElement).value)"
                      placeholder="Value"
                      class="px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <input
                      type="text"
                      :value="item.suffix"
                      @input="updateStatsItem(index, 'suffix', ($event.target as HTMLInputElement).value)"
                      placeholder="Suffix (e.g., +)"
                      class="px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                  <input
                    type="text"
                    :value="item.label"
                    @input="updateStatsItem(index, 'label', ($event.target as HTMLInputElement).value)"
                    placeholder="Label"
                    class="w-full mt-2 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <button
                    v-if="elementData.items.length > 1"
                    @click="removeStatsItem(index)"
                    class="mt-2 text-xs text-red-600 hover:text-red-800"
                  >
                    Remove
                  </button>
                </div>
              </div>
              <button
                @click="addStatsItem"
                class="mt-2 text-sm text-blue-600 hover:text-blue-800"
              >
                + Add Stat
              </button>
            </div>

            <!-- Columns -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Columns</label>
              <select
                :value="elementData.columns || '4'"
                @change="updateData('columns', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
              </select>
            </div>

            <!-- Animate -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.animate"
                  @change="updateData('animate', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Animate Numbers</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Card Grid Widget -->
        <div v-else-if="widgetType === 'card_grid'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Cards -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cards</label>
              <div class="space-y-3">
                <div
                  v-for="(card, index) in elementData.cards"
                  :key="index"
                  class="p-3 border border-gray-200 rounded-lg"
                >
                  <input
                    type="text"
                    :value="card.title"
                    @input="updateCardItem(index, 'title', ($event.target as HTMLInputElement).value)"
                    placeholder="Title"
                    class="w-full mb-2 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <textarea
                    :value="card.description"
                    @input="updateCardItem(index, 'description', ($event.target as HTMLTextAreaElement).value)"
                    placeholder="Description"
                    rows="2"
                    class="w-full mb-2 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  ></textarea>
                  <ElementorImageUpload
                    :model-value="card.image || ''"
                    @update:model-value="updateCardItem(index, 'image', $event)"
                    label="Image"
                    type="card"
                  />
                  <input
                    type="text"
                    :value="card.link"
                    @input="updateCardItem(index, 'link', ($event.target as HTMLInputElement).value)"
                    placeholder="Link URL (optional)"
                    class="w-full mb-2 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <button
                    v-if="elementData.cards.length > 1"
                    @click="removeCardItem(index)"
                    class="text-xs text-red-600 hover:text-red-800"
                  >
                    Remove
                  </button>
                </div>
              </div>
              <button
                @click="addCardItem"
                class="mt-2 text-sm text-blue-600 hover:text-blue-800"
              >
                + Add Card
              </button>
            </div>

            <!-- Columns -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Columns</label>
              <select
                :value="elementData.columns || '3'"
                @change="updateData('columns', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Icon Cards Widget -->
        <div v-else-if="widgetType === 'icon_cards'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Section Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                placeholder="Our Mission & Vision"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Subheading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Section Subheading</label>
              <textarea
                :value="elementData.subheading"
                @input="updateData('subheading', ($event.target as HTMLTextAreaElement).value)"
                rows="2"
                placeholder="Optional subheading text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <!-- Cards -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cards</label>
              <div class="space-y-4">
                <div
                  v-for="(card, index) in elementData.cards"
                  :key="index"
                  class="p-4 border border-gray-200 rounded-lg bg-gray-50"
                >
                  <div class="space-y-3">
                    <!-- Icon -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Icon</label>
                      <select
                        :value="card.icon || 'star'"
                        @input="updateIconCardItem(index, 'icon', ($event.target as HTMLSelectElement).value)"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      >
                        <option value="rocket">Rocket</option>
                        <option value="eye">Eye</option>
                        <option value="star">Star</option>
                        <option value="heart">Heart</option>
                        <option value="lightbulb">Lightbulb</option>
                        <option value="target">Target</option>
                        <option value="shield">Shield</option>
                        <option value="users">Users</option>
                      </select>
                    </div>

                    <!-- Icon Color -->
                    <ElementorColorPicker
                      :model-value="card.iconColor || '#3B82F6'"
                      @update:model-value="updateIconCardItem(index, 'iconColor', $event)"
                      label="Icon Color"
                    />

                    <!-- Border Color -->
                    <ElementorColorPicker
                      :model-value="card.borderColor || '#3B82F6'"
                      @update:model-value="updateIconCardItem(index, 'borderColor', $event)"
                      label="Border Color"
                    />

                    <!-- Title -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                      <input
                        type="text"
                        :value="card.title"
                        @input="updateIconCardItem(index, 'title', ($event.target as HTMLInputElement).value)"
                        placeholder="Our Mission"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <!-- Description -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                      <textarea
                        :value="card.description"
                        @input="updateIconCardItem(index, 'description', ($event.target as HTMLTextAreaElement).value)"
                        placeholder="Card description..."
                        rows="3"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      ></textarea>
                    </div>

                    <!-- Link -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Link URL (Optional)</label>
                      <input
                        type="text"
                        :value="card.link"
                        @input="updateIconCardItem(index, 'link', ($event.target as HTMLInputElement).value)"
                        placeholder="/about"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <!-- Link Text -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Link Text (Optional)</label>
                      <input
                        type="text"
                        :value="card.linkText"
                        @input="updateIconCardItem(index, 'linkText', ($event.target as HTMLInputElement).value)"
                        placeholder="Learn more"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <button
                      v-if="elementData.cards.length > 1"
                      @click="removeIconCardItem(index)"
                      class="text-xs text-red-600 hover:text-red-800 font-medium"
                    >
                      Remove Card
                    </button>
                  </div>
                </div>
              </div>
              <button
                @click="addIconCardItem"
                class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium"
              >
                + Add Card
              </button>
            </div>

            <!-- Columns -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Columns</label>
              <select
                :value="elementData.columns || '2'"
                @change="updateData('columns', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="1">1 Column</option>
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Timeline Widget -->
        <div v-else-if="widgetType === 'timeline'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Section Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                placeholder="Our History"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Heading Color -->
            <ElementorColorPicker
              :model-value="elementData.headingColor || '#1E40AF'"
              @update:model-value="updateData('headingColor', $event)"
              label="Heading Color"
            />

            <!-- Timeline Items -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Timeline Items</label>
              <div class="space-y-4">
                <div
                  v-for="(item, index) in elementData.items"
                  :key="index"
                  class="p-4 border border-gray-200 rounded-lg bg-gray-50"
                >
                  <div class="space-y-3">
                    <!-- Year -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Year/Label</label>
                      <input
                        type="text"
                        :value="item.year"
                        @input="updateTimelineItem(index, 'year', ($event.target as HTMLInputElement).value)"
                        placeholder="2006"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <!-- Year Color -->
                    <ElementorColorPicker
                      :model-value="item.yearColor || '#1E40AF'"
                      @update:model-value="updateTimelineItem(index, 'yearColor', $event)"
                      label="Year Color"
                    />

                    <!-- Title -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                      <input
                        type="text"
                        :value="item.title"
                        @input="updateTimelineItem(index, 'title', ($event.target as HTMLInputElement).value)"
                        placeholder="Milestone Title"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <!-- Description -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                      <textarea
                        :value="item.description"
                        @input="updateTimelineItem(index, 'description', ($event.target as HTMLTextAreaElement).value)"
                        placeholder="Description of this milestone..."
                        rows="3"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      ></textarea>
                    </div>

                    <!-- Background Color -->
                    <ElementorColorPicker
                      :model-value="item.backgroundColor || '#E5E7EB'"
                      @update:model-value="updateTimelineItem(index, 'backgroundColor', $event)"
                      label="Background Color"
                    />

                    <!-- Card Size -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Card Size</label>
                      <select
                        :value="item.size || 'normal'"
                        @input="updateTimelineItem(index, 'size', ($event.target as HTMLSelectElement).value)"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      >
                        <option value="normal">Normal</option>
                        <option value="large">Large</option>
                        <option value="full">Full Width</option>
                      </select>
                    </div>

                    <!-- Icon -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">Icon</label>
                      <select
                        :value="item.icon || 'none'"
                        @input="updateTimelineItem(index, 'icon', ($event.target as HTMLSelectElement).value)"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                      >
                        <option value="none">None</option>
                        <option value="rocket">Rocket</option>
                        <option value="star">Star</option>
                        <option value="trophy">Trophy</option>
                        <option value="globe">Globe</option>
                        <option value="users">Users</option>
                        <option value="lightbulb">Lightbulb</option>
                      </select>
                    </div>

                    <!-- Show Icon Toggle -->
                    <div>
                      <label class="flex items-center gap-2 cursor-pointer">
                        <input
                          type="checkbox"
                          :checked="item.showIcon !== false"
                          @change="updateTimelineItem(index, 'showIcon', ($event.target as HTMLInputElement).checked)"
                          class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                        />
                        <span class="text-xs font-medium text-gray-700">Show Year Watermark</span>
                      </label>
                    </div>

                    <button
                      v-if="elementData.items.length > 1"
                      @click="removeTimelineItem(index)"
                      class="text-xs text-red-600 hover:text-red-800 font-medium"
                    >
                      Remove Item
                    </button>
                  </div>
                </div>
              </div>
              <button
                @click="addTimelineItem"
                class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium"
              >
                + Add Timeline Item
              </button>
            </div>
          </div>
        </div>

        <!-- Contact Form Widget -->
        <div v-else-if="widgetType === 'contact_form'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <textarea
                :value="elementData.description"
                @input="updateData('description', ($event.target as HTMLTextAreaElement).value)"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <!-- Submit Text -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Submit Button Text</label>
              <input
                type="text"
                :value="elementData.submitText"
                @input="updateData('submitText', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Email To -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Send To Email</label>
              <input
                type="email"
                :value="elementData.emailTo"
                @input="updateData('emailTo', ($event.target as HTMLInputElement).value)"
                placeholder="info@example.com"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Show Phone -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showPhone"
                  @change="updateData('showPhone', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Phone Field</span>
              </label>
            </div>

            <!-- Show Company -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showCompany"
                  @change="updateData('showCompany', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Organization Field</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Search Box Widget -->
        <div v-else-if="widgetType === 'search_box'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Placeholder -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Placeholder Text</label>
              <input
                type="text"
                :value="elementData.placeholder"
                @input="updateData('placeholder', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Search Scope -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Search Scope</label>
              <select
                :value="elementData.searchScope || 'all'"
                @change="updateData('searchScope', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="all">All Content</option>
                <option value="pages">Pages Only</option>
                <option value="news">News Only</option>
                <option value="events">Events Only</option>
              </select>
            </div>

            <!-- Size -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
              <select
                :value="elementData.size || 'md'"
                @change="updateData('size', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="sm">Small</option>
                <option value="md">Medium</option>
                <option value="lg">Large</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Dynamic News Widget -->
        <div v-else-if="widgetType === 'dynamic_news'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Subheading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Subheading</label>
              <textarea
                :value="elementData.subheading"
                @input="updateData('subheading', ($event.target as HTMLTextAreaElement).value)"
                rows="2"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <!-- Limit -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Number of Items</label>
              <input
                type="number"
                :value="elementData.limit"
                @input="updateData('limit', parseInt(($event.target as HTMLInputElement).value))"
                min="1"
                max="20"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Layout -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Layout</label>
              <select
                :value="elementData.layout || 'grid'"
                @change="updateData('layout', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="grid">Grid</option>
                <option value="list">List</option>
                <option value="carousel">Carousel</option>
              </select>
            </div>

            <!-- Columns (for grid layout) -->
            <div v-if="elementData.layout === 'grid' || !elementData.layout">
              <label class="block text-sm font-medium text-gray-700 mb-2">Columns</label>
              <select
                :value="elementData.columns || '3'"
                @change="updateData('columns', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
              </select>
            </div>

            <!-- Show Image -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showImage"
                  @change="updateData('showImage', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Featured Image</span>
              </label>
            </div>

            <!-- Show Excerpt -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showExcerpt"
                  @change="updateData('showExcerpt', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Excerpt</span>
              </label>
            </div>

            <!-- Show Date -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showDate"
                  @change="updateData('showDate', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Date</span>
              </label>
            </div>

            <!-- Show Read More -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showReadMore"
                  @change="updateData('showReadMore', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Read More Link</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Dynamic Events Widget -->
        <div v-else-if="widgetType === 'dynamic_events'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Limit -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Number of Items</label>
              <input
                type="number"
                :value="elementData.limit"
                @input="updateData('limit', parseInt(($event.target as HTMLInputElement).value))"
                min="1"
                max="20"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Layout -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Layout</label>
              <select
                :value="elementData.layout || 'grid'"
                @change="updateData('layout', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="grid">Grid</option>
                <option value="list">List</option>
                <option value="timeline">Timeline</option>
              </select>
            </div>

            <!-- Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Show Events</label>
              <select
                :value="elementData.filter || 'upcoming'"
                @change="updateData('filter', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="upcoming">Upcoming Only</option>
                <option value="past">Past Only</option>
                <option value="all">All Events</option>
              </select>
            </div>

            <!-- Show Location -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showLocation"
                  @change="updateData('showLocation', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Location</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Dynamic Services Widget -->
        <div v-else-if="widgetType === 'dynamic_services'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Limit -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Number of Items</label>
              <input
                type="number"
                :value="elementData.limit"
                @input="updateData('limit', parseInt(($event.target as HTMLInputElement).value))"
                min="1"
                max="20"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Layout -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Layout</label>
              <select
                :value="elementData.layout || 'grid'"
                @change="updateData('layout', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="grid">Grid</option>
                <option value="list">List</option>
              </select>
            </div>

            <!-- Columns -->
            <div v-if="elementData.layout === 'grid' || !elementData.layout">
              <label class="block text-sm font-medium text-gray-700 mb-2">Columns</label>
              <select
                :value="elementData.columns || '3'"
                @change="updateData('columns', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
              </select>
            </div>

            <!-- Show Image -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showImage"
                  @change="updateData('showImage', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Image</span>
              </label>
            </div>

            <!-- Show Excerpt -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showExcerpt"
                  @change="updateData('showExcerpt', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Description</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Dynamic Members Widget -->
        <div v-else-if="widgetType === 'dynamic_members'">
          <div v-if="activeTab === 'Content'" class="space-y-4">
            <!-- Heading -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
              <input
                type="text"
                :value="elementData.heading"
                @input="updateData('heading', ($event.target as HTMLInputElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Limit -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Number of Items</label>
              <input
                type="number"
                :value="elementData.limit"
                @input="updateData('limit', parseInt(($event.target as HTMLInputElement).value))"
                min="1"
                max="50"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Layout -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Layout</label>
              <select
                :value="elementData.layout || 'grid'"
                @change="updateData('layout', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="grid">Grid</option>
                <option value="list">List</option>
              </select>
            </div>

            <!-- Columns -->
            <div v-if="elementData.layout === 'grid' || !elementData.layout">
              <label class="block text-sm font-medium text-gray-700 mb-2">Columns</label>
              <select
                :value="elementData.columns || '4'"
                @change="updateData('columns', ($event.target as HTMLSelectElement).value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
                <option value="6">6 Columns</option>
              </select>
            </div>

            <!-- Show Logo -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showLogo"
                  @change="updateData('showLogo', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Logo</span>
              </label>
            </div>

            <!-- Show Description -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="elementData.showDescription"
                  @change="updateData('showDescription', ($event.target as HTMLInputElement).checked)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Show Description</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Generic Widget (for widgets without specific settings) -->
        <div v-else>
          <div class="text-center py-8">
            <p class="text-sm text-gray-500">Settings for this widget are managed in the Filament admin panel.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  selectedElement: { type: 'section' | 'column' | 'widget'; id: string }
  sections: any[]
}>()

const emit = defineEmits(['update', 'close'])

const activeTab = ref('Content')
const availableTabs = computed(() => {
  if (props.selectedElement.type === 'section') {
    return ['Content', 'Style', 'Advanced']
  }
  return ['Content', 'Style']
})

// Get element data
const elementData = computed(() => {
  if (props.selectedElement.type === 'section') {
    const section = props.sections.find(s => s.id === props.selectedElement.id)
    return section?.settings || {}
  } else if (props.selectedElement.type === 'widget') {
    for (const section of props.sections) {
      for (const column of section.columns) {
        const widget = column.widgets.find((w: any) => w.id === props.selectedElement.id)
        if (widget) return widget.data || {}
      }
    }
  }
  return {}
})

const widgetType = computed(() => {
  if (props.selectedElement.type === 'widget') {
    for (const section of props.sections) {
      for (const column of section.columns) {
        const widget = column.widgets.find((w: any) => w.id === props.selectedElement.id)
        if (widget) return widget.type
      }
    }
  }
  return null
})

const elementTitle = computed(() => {
  if (props.selectedElement.type === 'section') {
    return 'Section'
  } else if (props.selectedElement.type === 'widget') {
    const { widgetLibrary } = useElementorEditor()
    const widgetDef = widgetLibrary.find(w => w.type === widgetType.value)
    return widgetDef?.label || 'Widget'
  }
  return 'Element'
})

const elementSubtitle = computed(() => {
  if (props.selectedElement.type === 'section') {
    return 'Configure section layout and styling'
  } else if (props.selectedElement.type === 'widget') {
    return 'Edit widget content and appearance'
  }
  return ''
})

// Update functions
const updateSetting = (key: string, value: any) => {
  emit('update', { [key]: value })
}

const updateData = (key: string, value: any) => {
  emit('update', { [key]: value })
}

const updatePadding = (side: string, value: string) => {
  const padding = { ...elementData.value.padding }
  padding[side] = parseInt(value) || 0
  emit('update', { padding })
}

const updateMargin = (side: string, value: string) => {
  const margin = { ...elementData.value.margin }
  margin[side] = parseInt(value) || 0
  emit('update', { margin })
}

const updateTypography = (key: string, value: any) => {
  const typography = { ...elementData.value.typography }
  typography[key] = value
  emit('update', { typography })
}

const updateImageSize = (key: string, value: any) => {
  const width = { ...elementData.value.width }
  width[key] = value
  emit('update', { width })
}

const updateSpaceSize = (key: string, value: any) => {
  const space = { ...elementData.value.space }
  space[key] = value
  emit('update', { space })
}

const updateWeightSize = (key: string, value: any) => {
  const weight = { ...elementData.value.weight }
  weight[key] = value
  emit('update', { weight })
}

// Helper functions for array manipulation
const updateStatsItem = (index: number, key: string, value: any) => {
  const items = [...elementData.value.items]
  items[index] = { ...items[index], [key]: value }
  emit('update', { items })
}

const addStatsItem = () => {
  const items = [...elementData.value.items]
  items.push({ value: '0', suffix: '', label: 'New Stat' })
  emit('update', { items })
}

const removeStatsItem = (index: number) => {
  const items = [...elementData.value.items]
  items.splice(index, 1)
  emit('update', { items })
}

const updateCardItem = (index: number, key: string, value: any) => {
  const cards = [...elementData.value.cards]
  cards[index] = { ...cards[index], [key]: value }
  emit('update', { cards })
}

const addCardItem = () => {
  const cards = [...elementData.value.cards]
  cards.push({ image: '', title: 'New Card', description: '', link: '' })
  emit('update', { cards })
}

const removeCardItem = (index: number) => {
  const cards = [...elementData.value.cards]
  cards.splice(index, 1)
  emit('update', { cards })
}

// Icon Cards helpers
const updateIconCardItem = (index: number, key: string, value: any) => {
  const cards = [...elementData.value.cards]
  cards[index] = { ...cards[index], [key]: value }
  emit('update', { cards })
}

const addIconCardItem = () => {
  const cards = [...elementData.value.cards]
  cards.push({
    icon: 'star',
    iconColor: '#3B82F6',
    borderColor: '#3B82F6',
    title: 'New Card',
    description: 'Card description goes here.',
    link: '',
    linkText: ''
  })
  emit('update', { cards })
}

const removeIconCardItem = (index: number) => {
  const cards = [...elementData.value.cards]
  cards.splice(index, 1)
  emit('update', { cards })
}

// Timeline helpers
const updateTimelineItem = (index: number, key: string, value: any) => {
  const items = [...elementData.value.items]
  items[index] = { ...items[index], [key]: value }
  emit('update', { items })
}

const addTimelineItem = () => {
  const items = [...elementData.value.items]
  items.push({
    year: new Date().getFullYear().toString(),
    yearColor: '#1E40AF',
    title: 'New Milestone',
    description: 'Description of this milestone...',
    backgroundColor: '#E5E7EB',
    size: 'normal',
    icon: 'none',
    showIcon: true
  })
  emit('update', { items })
}

const removeTimelineItem = (index: number) => {
  const items = [...elementData.value.items]
  items.splice(index, 1)
  emit('update', { items })
}

// Reset active tab when element changes
watch(() => props.selectedElement, () => {
  activeTab.value = 'Content'
})
</script>
