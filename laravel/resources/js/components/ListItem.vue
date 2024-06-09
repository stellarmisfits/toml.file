<template>
  <a-well>
    <div class="lg:flex">
      <div class="relative pb-2/3 lg:flex-none lg:pb-0 h-32 w-32">
        <img v-if="imageUrl" class="absolute h-full w-full object-cover" :src="imageUrl" :alt="imageTitle">
        <div v-else class="absolute flex justify-center items-center h-full w-full object-cover bg-gray-400">
          <fa class="block text-white text-5xl" icon="image" />
        </div>
        <slot name="imageAction" />
      </div>
      <div class="flex flex-grow p-6">
        <div class="flex-grow">
          <router-link v-if="routerLink" :to="routerLink" class="hover:bg-gray-100">
            <slot name="body" />
          </router-link>
          <slot v-else name="body" />
        </div>
        <div v-if="$slots['action']" class="flex-none">
          <slot name="action" />
        </div>
      </div>
    </div>
    <div v-if="$slots['details']" class="p-6">
      <slot name="details" />
    </div>
  </a-well>
</template>
<script>
export default {
  name: 'AListItem',
  components: {
    //
  },
  props: {
    imageUrl: {
      required: true,
      validator: prop => typeof prop === 'string' || prop === null
    },
    imageTitle: { type: String, required: true },
    routerLink: { type: Object, default: null }
  }
}
</script>
