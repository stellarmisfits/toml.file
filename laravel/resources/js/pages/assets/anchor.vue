<template>
  <div class="py-8 mx-auto">
    <div class="flex items-baseline justify-between">
      <div>
        <h2 class="text-light-heading">
          Asset Anchor
        </h2>
        <div class="mt-1 text-light-secondary">
          <div class="max-w-2xl">
            If this token can be redeemed for an underlying asset the anchor's
            details should be defined here.
          </div>
        </div>
      </div>
      <div class="flex-shrink-0 ml-4">
        <anchor-switch :key="asset.uuid + asset.anchored" :asset="asset" />
      </div>
    </div>
    <hr class="my-10 border-b border-gray-800">
    <div>
      <asset-anchor :asset="asset" />
    </div>
  </div>
</template>

<script>
import AnchorSwitch from '~/components/assets/AnchorSwitch'
import AssetAnchor from '~/components/assets/Anchor'

export default {
  middleware: 'auth',

  components: {
    AnchorSwitch,
    AssetAnchor
  },

  props: {
    asset: { type: Object, required: true }
  },

  metaInfo () {
    return { title: 'Asset Anchor' }
  },

  data: () => ({
    //
  }),

  methods: {
    //
  }
}
</script>
