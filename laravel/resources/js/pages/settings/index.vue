<template>
  <div class="flex-grow">
    <a-breadcrumbs :tabs="tabs">
      <span slot="title">Account Settings</span>
    </a-breadcrumbs>
    <div class="px-12 py-8 mx-auto max-w-4xl">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
</template>

<script>
import ABreadcrumbs from '~/components/Breadcrumbs'
export default {
  components: { ABreadcrumbs },
  middleware: 'auth',
  computed: {
    tabs () {
      return [
        {
          name: 'Profile',
          route: 'settings.profile'
        },
        {
          name: 'Password',
          route: 'settings.password'
        }
      ]
    }
  },
  created () {
    if (!window.config.loginEnabled) {
      this.$router.push('/app')
    }
  }
}
</script>
