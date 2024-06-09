<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-200">
    <router-link :to="{ name: 'welcome' }">
      <a-logo class="h-24 w-24 text-blue-800 hover:text-gray-600" />
    </router-link>
    <a-well class="mt-6">
      <Login class="px-6 py-6 w-96" />
    </a-well>
  </div>
</template>

<script>
import Login from '~/components/auth/Login'

export default {
  layout: 'auth',
  middleware: 'guest',

  components: {
    Login
  },

  metaInfo () {
    return { title: this.$t('login') }
  },
  created () {
    if (!window.config.loginEnabled) {
      this.$router.push('/')
    }
  }
}
</script>
