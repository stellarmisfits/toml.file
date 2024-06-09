<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-200">
    <router-link :to="{ name: 'welcome' }">
      <a-logo class="mt-6 h-24 w-24 text-blue-800 hover:text-gray-600" />
    </router-link>
    <a-well class="my-6">
      <privacy class="px-6 py-6 w-128" />
    </a-well>
  </div>
</template>

<script>
import Privacy from '~/components/auth/Privacy'

export default {
  layout: 'auth',

  components: {
    Privacy
  },

  metaInfo () {
    return { title: 'Privacy Policy' }
  },

  data: () => ({
    //
  }),

  methods: {
    //
  }
}
</script>
