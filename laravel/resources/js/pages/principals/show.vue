<template>
  <div v-if="principal" class="flex-grow">
    <a-breadcrumbs>
      <span slot="title">
        <router-link :to="{ name: 'principals' }">
          Principals
        </router-link>
        <span class="mx-3 text-gray-400 font-light text-2xl leading-none">/</span>{{ principal.name }}</span>
      <delete-resource
        slot="dropdown"
        :resource="principal"
        resource-type="PRINCIPAL"
      />
    </a-breadcrumbs>
    <div class="py-8 mx-auto max-w-4xl">
      <transition name="fade" mode="out-in">
        <router-view :principal="principal" />
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
          route: 'principal.details',
          params: { uuid: this.$route.params.uuid }
        }
      ]
    }
  },
  computed: {
    ...mapGetters('principal', ['getPrincipalByUuid']),
    principal: function () {
      return this.getPrincipalByUuid(this.$route.params.uuid)
    }
  },
  async created () {
    try {
      await this.$store.dispatch('principal/fetchPrincipal', this.$route.params.uuid)
    } catch (e) {
      if (e.response.status === 404) {
        this.$router.push({ name: '404' })
      }
    }
  }
}
</script>
