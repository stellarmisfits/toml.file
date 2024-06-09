<template>
  <div>
    <div class="flex items-center justify-center">
      <fa
        class="text-5xl fill-current text-gray-400"
        icon="file"
      />
    </div>
    <div class="mt-4 text-center max-w-xl mx-auto">
      <slot>{{ message }}</slot>
    </div>
  </div>
</template>
<script>
export default {
  name: 'AEmptyList',
  props: {
    message: {
      type: String,
      required: false,
      default: 'No results'
    }
  }
}
</script>
