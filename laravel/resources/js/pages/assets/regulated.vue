<template>
  <div class="py-8 mx-auto">
    <div class="flex items-baseline justify-between">
      <div>
        <h2 class="text-light-heading">
          Asset Regulated
        </h2>
        <div class="mt-1 text-light-secondary">
          <div class="max-w-2xl">
            Indicates whether or not this is a <a target="_blank" class="underline" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0008.md">SEP: 0008</a> regulated asset. If missing, false is assumed.
          </div>
        </div>
      </div>
      <div class="flex-shrink-0 ml-4">
        <regulated-switch
          :key="asset.uuid + asset.regulated"
          :asset="asset"
        />
      </div>
    </div>
    <div class="mt-4">
      <asset-regulated :asset="asset" />
    </div>
  </div>
</template>

<script>
import AssetRegulated from '~/components/assets/Regulated'
import RegulatedSwitch from '~/components/assets/RegulatedSwitch'

export default {
  middleware: 'auth',

  components: {
    AssetRegulated,
    RegulatedSwitch
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
