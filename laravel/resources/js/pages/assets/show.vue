<template>
  <div v-if="asset" class="flex-grow">
    <a-breadcrumbs :tabs="tabs">
      <span slot="title">
        <router-link :to="{ name: 'assets' }">
          Assets
        </router-link>
        <span class="mx-3 text-gray-400 font-light text-2xl leading-none">/</span>{{ asset.name }}</span>
      <delete-resource
        slot="dropdown"
        :resource="asset"
        resource-type="ASSET"
      />
    </a-breadcrumbs>
    <div class="px-12 py-8 mx-auto max-w-5xl">
      <transition name="fade" mode="out-in">
        <router-view :asset="asset" />
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
          route: 'asset.details',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Anchor',
          route: 'asset.anchor',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Regulated',
          route: 'asset.regulated',
          params: { uuid: this.$route.params.uuid }
        },
        {
          name: 'Network Details',
          route: 'asset.network',
          params: { uuid: this.$route.params.uuid }
        }
      ]
    }
  },
  computed: {
    ...mapGetters('asset', ['getAssetByUuid']),
    asset: function () {
      return this.getAssetByUuid(this.$route.params.uuid)
    }
  },
  async created () {
    try {
      await this.$store.dispatch('asset/fetchAsset', {
        uuid: this.$route.params.uuid
      })
    } catch (e) {
      if (e.response.status === 404) {
        this.$router.push({ name: '404' })
      }
    }
  }
}
</script>
