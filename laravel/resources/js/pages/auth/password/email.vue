<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-200">
    <router-link :to="{ name: 'dashboard' }">
      <a-logo class="h-24 w-24 text-blue-800 hover:text-gray-600" />
    </router-link>
    <a-well class="mt-6 w-96">
      <email class="px-6 py-6 max-w-md" />
    </a-well>
  </div>
</template>

<script>
import Email from '~/components/auth/Email'

export default {
  layout: 'auth',
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('reset_password') }
  },

  components: {
    Email
  },

  data: () => ({
    //
  }),

  created () {
    if (!window.config.loginEnabled) {
      this.$router.push('/')
    }
  },

  methods: {
    //
  }
}
</script>
