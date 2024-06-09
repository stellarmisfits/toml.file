<template>
  <div v-if="account" class="flex-grow">
    <a-breadcrumbs :tabs="tabs">
      <span slot="title">
        <router-link :to="{ name: 'accounts' }">
          Accounts
        </router-link>
        <span class="mx-3 text-gray-400 font-light text-2xl leading-none">/</span>{{ account.alias }}</span>
      <delete-resource
        slot="dropdown"
        :resource="account"
        resource-type="ACCOUNT"
      />
    </a-breadcrumbs>
    <div class="px-12 py-8 mx-auto max-w-4xl">
      <transition name="fade" mode="out-in">
        <router-view :account="account" />
      </transition>
    </div>
  </div>
</template>

<script>
import ABreadcrumbs from '~/components/Breadcrumbs'
import DeleteResource from '~/components/DeleteResource'
import { mapGetters } from 'vuex'
export default {
  components: { ABreadcrumbs, DeleteResource },
  middleware: 'auth',
  data () {
    return {
      tabs: [
        {
          name: 'General',
          route: 'account.details',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Network Details',
          route: 'account.network',
          params: { uuid: this.$route.params.uuid }
        }
      ]
    }
  },
  computed: {
    ...mapGetters('account', ['getAccountByUuid']),
    account: function () {
      return this.getAccountByUuid(this.$route.params.uuid)
    }
  },
  async created () {
    try {
      await this.$store.dispatch('account/fetchAccount', this.$route.params.uuid)
    } catch (e) {
      if (e.response.status === 404) {
        this.$router.push({ name: '404' })
      }
    }
  }
}
</script>
