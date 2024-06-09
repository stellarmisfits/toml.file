<template>
  <div>
    <Dropdown v-if="user">
      <span slot="link" class="flex items-center">
        <img :src="user.photo_url" class="h-8 w-8 rounded-full">
        <span class="ml-3">
          {{ user.name }}
        </span>
        <fa class="ml-2 fill-current text-gray-600" icon="chevron-circle-down" />
      </span>
      <div slot="dropdown" class="py-2 w-64">
        <router-link v-if="showSettings" :to="{ name: 'settings.profile' }" class="dropdown-item">
          <fa class="mr-2" icon="cog" />Settings
        </router-link>
        <a href="#" class="dropdown-item" @click.prevent="logout">
          <fa class="mr-2" icon="sign-out-alt" />{{ $t('logout') }}
        </a>
      </div>
    </Dropdown>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
    open: false
  }),

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    showSettings: function () {
      return window.config.loginEnabled
    }
  },

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.go({ name: 'login' })
    }
  }
}
</script>
