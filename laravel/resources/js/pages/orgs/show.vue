<template>
  <div v-if="organization" class="flex-grow">
    <a-breadcrumbs :tabs="tabs">
      <span slot="title">
        <router-link :to="{ name: 'dashboard' }">
          Organizations
        </router-link>
        <span class="mx-3 text-gray-400 font-light text-2xl leading-none">/</span>{{ organization.name }}
      </span>
      <delete-resource
        slot="dropdown"
        :resource="organization"
        resource-type="ORGANIZATION"
      >
        <span slot="title">
          Are you sure you want to delete the "{{ organization.name }}" organization?
        </span>
      </delete-resource>
    </a-breadcrumbs>
    <div class="px-12 py-8 mx-auto max-w-5xl">
      <transition name="fade" mode="out-in">
        <router-view :organization="organization" />
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
          route: 'org.details',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Resources',
          route: 'org.resources',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Documentation',
          route: 'org.documentation',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Globals',
          route: 'org.globals',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Toml',
          route: 'org.toml',
          params: { uuid: this.$route.params.uuid }
        }
      ]
    }
  },
  computed: {
    ...mapGetters('org', ['getOrgByUuid']),
    ...mapGetters('account', ['accounts']),
    organization: function () {
      const uuid = this.$route.params.uuid
      return this.getOrgByUuid(uuid)
    }
  },
  async created () {
    try {
      await this.$store.dispatch('org/fetchOrg', this.$route.params.uuid)
    } catch (e) {
      if (e.response.status === 404) {
        this.$router.push({ name: '404' })
      }
    }
  }
}
</script>
