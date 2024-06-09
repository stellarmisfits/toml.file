<template>
  <div>
    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-lg" />
        </div>
      </div>
      <a-well class="mt-4">
        <div v-if="horizonAccountNotFound" class="px-6 py-4">
          The given account was not found on the stellar network. This usually
          occurs when the provided public key has not been funded.
        </div>
        <div v-else>
          <div class="px-6 py-4 bg-blue-800 text-light">
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                Network Details
              </div>
              <a-pill class="ml-2" :color="(horizonType === 'testnet') ? 'red' : 'green'">
                {{ horizonType }}
              </a-pill>
            </div>
            <div class="mt-2 text-xs">
              {{ horizonAccount.id }}
            </div>
          </div>

          <!-- sequence -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Sequence
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAccount.sequence }}
                </span>
              </div>
            </div>
          </div>

          <!-- subentry_count -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Sub-entry Count
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAccount.subentry_count }}
                </span>
              </div>
            </div>
          </div>

          <!-- home_domain -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Home Domain
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAccount.home_domain }}
                </span>
              </div>
            </div>
          </div>

          <!-- last_modified_ledger -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Last Modified Ledger
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAccount.last_modified_ledger }}
                </span>
              </div>
            </div>
          </div>

          <div class="px-6 py-2 bg-gray-100 border-t text-xs text-gray-700 font-bold uppercase tracking-wider">
            Thresholds
          </div>

          <!-- low_threshold -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Low
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAccount.thresholds.low_threshold }}
                </span>
              </div>
            </div>
          </div>

          <!-- med_threshold -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                Medium
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAccount.thresholds.med_threshold }}
                </span>
              </div>
            </div>
          </div>

          <!-- high_threshold -->
          <div class="border-t border-gray-200">
            <div class="px-6 py-2 flex justify-between text-sm text-gray-700">
              <div>
                High
              </div>
              <div>
                <span class="text-gray-500">
                  {{ horizonAccount.thresholds.high_threshold }}
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
                  {{ horizonAccount.flags.auth_required }}
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
                  {{ horizonAccount.flags.auth_revocable }}
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
                  {{ horizonAccount.flags.auth_immutable }}
                </span>
              </div>
            </div>
          </div>

          <!-- balances -->
          <div class="px-6 py-2 bg-gray-100 border-t text-xs text-gray-700 font-bold uppercase tracking-wider">
            Balances
          </div>

          <div
            v-for="balance in horizonAccount.balances"
            :key="balance.asset_type"
            class="border-t border-gray-200"
          >
            <div class="px-6 pt-2 flex justify-between text-sm text-gray-700">
              <div v-if="balance.asset_type === 'native'">
                XLM
              </div>
              <div v-else>
                <div>{{ balance.asset_code }} @ {{ balance.asset_issuer }}</div>
              </div>
              <div>
                <span class="text-gray-500">
                  {{ balance.balance }}
                </span>
              </div>
            </div>
            <div class="px-6 py-2 spaced-y-1 text-xs text-gray-600">
              <template v-if="balance.asset_type !== 'native'">
                <div class="flex justify-between">
                  <div class="flex items-center">
                    Asset type
                  </div>
                  <div class="text-gray-500">
                    {{ balance.asset_type }}
                  </div>
                </div>
                <div class="flex justify-between">
                  <div class="flex items-center">
                    Is authorized
                  </div>
                  <div class="text-gray-500">
                    {{ balance.is_authorized }}
                  </div>
                </div>
                <div class="flex justify-between">
                  <div class="flex items-center">
                    Last modified ledger
                  </div>
                  <div class="text-gray-500">
                    {{ balance.last_modified_ledger }}
                  </div>
                </div>
              </template>
              <div class="flex justify-between">
                <div class="flex items-center">
                  Buying liabilities
                </div>
                <div class="text-gray-500">
                  {{ balance.buying_liabilities }}
                </div>
              </div>
              <div class="flex justify-between">
                <div class="flex items-center">
                  Selling liabilities
                </div>
                <div class="text-gray-500">
                  {{ balance.selling_liabilities }}
                </div>
              </div>
            </div>
          </div>

          <!-- signers -->
          <div class="px-6 py-2 bg-gray-100 border-t text-xs text-gray-700 font-bold uppercase tracking-wider">
            Signers
          </div>

          <div
            v-for="signer in horizonAccount.signers"
            :key="signer.key"
            class="border-t border-gray-200"
          >
            <div class="px-6 pt-2 flex justify-between text-sm text-gray-700">
              <div>
                {{ signer.key }}
              </div>
              <div>
                <span class="text-gray-500">
                  weight {{ signer.weight }}
                </span>
              </div>
            </div>
            <div class="px-6 py-2 spaced-y-1 text-xs text-gray-600">
              {{ signer.type }}
            </div>
          </div>
        </div>
      </a-well>
    </div>
  </div>
</template>

<script>
// import OrganizationList from '~/components/orgs/List'
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',

  components: {
    // OrganizationList,
  },

  props: {
    account: { type: Object, required: true }
  },

  metaInfo () {
    return { title: 'Account Network Details' }
  },

  data: () => ({
    //
  }),

  computed: {
    ...mapGetters({
      horizonAccount: 'horizon-account/getAccount',
      horizonAccountNotFound: 'horizon-account/getAccountNotFound'
    }),
    horizonType () {
      return window.config.horizonType
    }
  },

  async created () {
    try {
      await this.$store.dispatch('horizon-account/fetchAccount', this.account.public_key)
    } catch (e) {
      console.log(e)
    }
  }
}
</script>
