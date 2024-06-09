<template>
  <div class="inline-block px-2 py-0 rounded-full text-xs font-semibold tracking-wider leading-relaxed uppercase" :class="type">
    <slot />
  </div>
</template>
<script>
export default {
  name: 'APill',
  props: {
    color: { type: String, required: true }
  },
  computed: {
    type () {
      return `bg-${this.color}-100 text-${this.color}-800`
    }
  }
}
</script>
