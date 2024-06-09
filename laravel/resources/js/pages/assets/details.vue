<template>
  <div>
    <div class="py-8 mx-auto">
      <div class="mt-4">
        <asset :asset="asset" action="edit" />
      </div>
    </div>

    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading">
            Asset Metrics
          </h2>
        </div>
      </div>
      <div class="mt-4">
        <asset-metrics :asset="asset" />
      </div>
    </div>
    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading">
            Asset Account
          </h2>
          <div class="mt-1 text-light-secondary">
            This asset is issued by the following account. You can change the account the asset is tied to by clicking the edit icon above.
          </div>
        </div>
      </div>
      <div class="mt-4">
        <account
          v-if="account"
          :account="account"
          action="navigate"
        />
      </div>
    </div>
    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading">
            Organizations
          </h2>
          <div class="mt-1 text-light-secondary">
            This Asset is linked to the following organizations through it's issuing account.
          </div>
        </div>
      </div>
      <div class="mt-4">
        <organization
          v-if="organization"
          action="navigate"
          :org="organization"
        />
        <a-well v-else class="px-6 py-12">
          <a-empty-list
            message="This asset is not tied to an organization."
          />
        </a-well>
      </div>
    </div>
  </div>
</template>

<script>
import Asset from '~/components/assets/Asset'
import Account from '~/components/accounts/Account'
import AssetMetrics from '~/components/assets/Metrics'
import Organization from '~/components/orgs/Organization'
import { mapGetters } from 'vuex'
export default {
  middleware: 'auth',

  components: {
    Organization,
    Asset,
    AssetMetrics,
    Account
  },

  props: {
    asset: { type: Object, required: true }
  },

  metaInfo () {
    return { title: 'Asset Details' }
  },

  data: () => ({
    //
  }),

  computed: {
    ...mapGetters('account', ['getAccountByUuid']),
    ...mapGetters('org', ['getOrgByUuid']),
    account: function () {
      return this.getAccountByUuid(this.asset.account_uuid)
    },
    organization: function () {
      if (this.account) {
        console.log(this.account.organization_uuid)
        return this.getOrgByUuid(this.account.organization_uuid)
      }

      return null
    }
  },

  created () {
    if (this.asset.account_uuid) {
      this.$store.dispatch('account/fetchAccount', this.asset.account_uuid)
    }

    this.updateOrgs()
  },

  methods: {
    updateOrgs () {
      this.$store.dispatch('asset/fetchLinkedOrgs', { resourceUuid: this.asset.uuid, resourceType: 'assets' })
      this.$store.dispatch('asset/fetchUnlinkedOrgs', { resourceUuid: this.asset.uuid, resourceType: 'assets' })
    }
  }
}
</script>
