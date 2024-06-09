<template>
  <div class="bg-blue-900">
    <div class="px-12">
      <div class="py-6">
        <div class="flex justify-between items-center">
          <div
            class="flex items-center text-sm text-light-heading uppercase font-bold tracking-widest"
          >
            <slot name="title" />
          </div>
          <div>
            <slot name="dropdown" />
          </div>
        </div>
        <div v-if="hasSubtitleSlot" class="mt-2 text-sm text-light hover:text-gray-900">
          <slot name="subtitle" />
        </div>
        <div v-if="hasMenuSlot" class="mt-6 pt-6 border-t border-blue-800">
          <nav class="flex spaced-x-4 text-light text-sm">
            <router-link
              v-for="tab in tabs"
              :key="tab.href"
              :to="{ name: tab.route }"
              active-class="text-gray-400 underline"
              class="hover:text-gray-500"
            >
              {{ tab.name }}
            </router-link>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ABreadcrumbs',
  props: {
    tabs: {
      type: Array,
      default: null
    }
  },
  data () {
    return {
      //
    }
  },
  computed: {
    hasSubtitleSlot () {
      return !!this.$slots['subtitle']
    },
    hasMenuSlot () {
      return !!this.tabs
    }
  }
}
</script>
