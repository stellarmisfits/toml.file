<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-200">
    <router-link :to="{ name: 'dashboard' }">
      <a-logo class="h-24 w-24 text-blue-800 hover:text-gray-600" />
    </router-link>
    <a-well class="mt-6">
      <register class="px-6 py-6 w-128" />
    </a-well>
  </div>
</template>

<script>
import Register from '~/components/auth/Register'

export default {
  middleware: 'guest',
  layout: 'auth',

  components: {
    Register
  },

  metaInfo () {
    return { title: this.$t('register') }
  },

  data: () => ({
    //
  }),

  created () {
    if (!window.config.registrationEnabled) {
      this.$router.push('/')
    }
  },

  methods: {
    //
  }
}
</script>
