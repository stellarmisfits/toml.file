<template>
  <div>
    <div class="py-8 mx-auto">
      <a-well class="mt-4">
        <div v-if="horizonAssetNotFound" class="px-6 py-4">
          The given asset was not found on the stellar network. This usually
          occurs when no assets have been transferred.
        </div>
        <div v-else>
          <div class="px-6 py-4 bg-blue-800 text-light">
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                Asset Details
              </div>
              <a-pill class="ml-2" :color="(horizonType === 'testnet') ? 'red' : 'green'">
                {{ horizonType }}
              </a-pill>
            </div>
            <div class="mt-2 text-xs">
              {{ horizonAsset.paging_token }}
            </div>
          </div>

          <!-- asset_type -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Asset Type
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAsset.asset_type }}
                </span>
              </div>
            </div>
          </div>

          <!-- amount -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Amount
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAsset.amount }}
                </span>
              </div>
            </div>
          </div>

          <!-- num_accounts -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                The number of accounts that either trust the asset or are authorized if required.
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAsset.num_accounts }}
                </span>
              </div>
            </div>
          </div>

          <div class="px-6 py-2 bg-gray-100 border-t text-xs text-gray-700 font-bold uppercase tracking-wider">
            Flags
          </div>

          <!-- auth_required -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Auth Required
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAsset.flags.auth_required }}
                </span>
              </div>
            </div>
          </div>

          <!-- auth_revocable -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Auth Revocable
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAsset.flags.auth_revocable }}
                </span>
              </div>
            </div>
          </div>

          <!-- auth_immutable -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Auth Immutable
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAsset.flags.auth_immutable }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </a-well>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',

  components: {
    //
  },

  props: {
    asset: { type: Object, required: true }
  },

  metaInfo () {
    return { title: 'Asset Network Details' }
  },

  data: () => ({
    //
  }),

  computed: {
    ...mapGetters({
      horizonAsset: 'horizon-asset/getAsset',
      horizonAssetNotFound: 'horizon-asset/getAssetNotFound'
    }),
    horizonType () {
      return window.config.horizonType
    }
  },

  async created () {
    try {
      await this.$store.dispatch('horizon-asset/fetchAsset', this.asset)
    } catch (e) {
      console.log(e)
    }
  }
}
</script>
