<template>
  <div>
    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading">
            Account Details
          </h2>
        </div>
      </div>
      <div class="mt-4">
        <account
          :account="account"
          action="edit"
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
            <div class="max-w-2xl">
              This account is linked to the following organizations.
            </div>
          </div>
        </div>
        <div class="flex-shrink-0 ml-4">
          <link-organization
            resource-type="account"
            :resource-uuid="account.uuid"
            :unlinked-orgs="unlinkedOrgs"
            @organizationLinked="updateOrgs"
          />
        </div>
      </div>
      <div class="mt-4">
        <OrganizationList
          action="unlink"
          :orgs="linkedOrgs"
          empty-message="No organizations found."
          resource-owner-type="account"
          :resource-owner-uuid="account.uuid"
          @organizationUnlinked="updateOrgs"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Account from '~/components/accounts/Account'
import OrganizationList from '~/components/orgs/List'
import LinkOrganization from '~/components/orgs/OrganizationLink'
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',

  components: {
    OrganizationList,
    LinkOrganization,
    Account
  },

  props: {
    account: { type: Object, required: true }
  },

  metaInfo () {
    return { title: 'Account Details' }
  },

  data: () => ({
    //
  }),

  computed: {
    ...mapGetters({
      linkedOrgs: 'account/linkedOrgs',
      unlinkedOrgs: 'account/unlinkedOrgs'
    })
  },

  created () {
    this.updateOrgs()
  },

  methods: {
    updateOrgs () {
      this.$store.dispatch('account/fetchLinkedOrgs', { resourceUuid: this.account.uuid, resourceType: 'accounts' })
      this.$store.dispatch('account/fetchUnlinkedOrgs', { resourceUuid: this.account.uuid, resourceType: 'accounts' })
    }
  }
}
</script>
