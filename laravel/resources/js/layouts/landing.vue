<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-3">
      <router-link :to="{ name: 'welcome' }" class="flex items-center text-blue-800 hover:text-gray-600">
        <a-logo class="h-8 w-8" />
        <span class="ml-2 text-lg">Astrify</span>
      </router-link>

      <div class="flex items-center">
        <router-link v-if="loginEnabled" :to="{ name: 'login' }" class="btn-flat flex items-center hover:text-gray-600">
          Login
        </router-link>
        <!--<a href="https://github.com/astrify/astrify" target="_blank"><fa :icon="['fab', 'github']" class="ml-2 text-3xl text-blue-800 hover:text-gray-600" /></a>-->
      </div>
    </div>

    <child class="flex-grow" />

    <!-- Footer -->
    <div class="p-6 bg-blue-900 text-blue-100">
      <div class="flex items-center justify-between">
        <div>
          © Jelled Media LLC - {{ new Date().getFullYear() }}
        </div>
        <div>
          <a class="hover:text-gray-600" href="/terms" target="_blank">Terms of Service</a> -
          <a class="hover:text-gray-600" href="/privacy" target="_blank">Privacy</a> -
          <a class="hover:text-gray-600" href="https://github.com/astrify/astrify/issues" target="_blank">Support</a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  layout: 'auth',
  middleware: 'guest',

  components: {
    //
  },

  metaInfo () {
    return { title: this.$t('login') }
  },

  computed: {
    loginEnabled () {
      return window.config.loginEnabled
    }
  }
}
</script>
