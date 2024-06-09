<template>
  <div v-if="assets && assets.length" class="spaced-y-6">
    <Asset
      v-for="asset in assets"
      :key="asset.uuid"
      :asset="asset"
      :action="action"
    />
  </div>
  <a-well v-else class="px-6 py-12">
    <a-empty-list
      :message="emptyMessage"
    />
  </a-well>
</template>
<script>
import Asset from '~/components/assets/Asset'
import { mapGetters } from 'vuex'
export default {
  components: {
    Asset
  },
  props: {
    assets: { type: Array, default: () => [] },
    emptyMessage: { type: String, required: true },
    action: { type: String, default: null }
  },
  data: () => ({
    //
  }),
  computed: {
    ...mapGetters({
      //
    })
  }
}
</script>
