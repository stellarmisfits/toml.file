<template>
  <div>
    <div v-if="open" class="fixed inset-0 z-40" @click.prevent="open=false" />
    <div class="relative">
      <button type="button" class="block w-full focus:outline-none" @click="open = !open">
        <slot name="link" />
      </button>
      <transition
        enter-active-class="transition-all transition-duration-50 transition-ease-out"
        leave-active-class="transition-all transition-duration-50 transition-ease-in"
        enter-class="opacity-0 scale-75"
        enter-to-class="opacity-100 scale-100"
        leave-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-75"
      >
        <div v-if="open" class="absolute z-50 mt-2 rounded-lg border shadow-md bg-white overflow-hidden right-0 transform-tr">
          <slot name="dropdown" :closeDropdown="close" />
        </div>
      </transition>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Dropdown',
  data: () => ({
    open: false
  }),
  methods: {
    close () {
      this.open = false
    }
  }
}
</script>
