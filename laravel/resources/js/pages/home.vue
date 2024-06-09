<template>
  <div class="flex-grow flex flex-col">
    <a-breadcrumbs>
      <span slot="title">Dashboard</span>
    </a-breadcrumbs>
    <div class="px-12 py-8 mx-auto max-w-4xl">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-lg text-light-heading">
            Organizations
          </h2>
          <div class="mt-2 text-sm text-light-secondary">
            <div class="max-w-2xl">
              All of the organizations your account has access to will be
              listed here. Organizations are the building blocks of TOML files
              on the Stellar network. Assets must be tied to an organization through
              a Steallar account. Read more about TOML files and organizations <a class="underline hover:text-gray-500" target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0001.md#issuer-documentation">here</a>.
            </div>
          </div>
        </div>
        <div class="flex-shrink-0 ml-4">
          <create-org
            action="create"
          />
        </div>
      </div>
      <div class="mt-4">
        <list-orgs
          :orgs="orgs"
          action="navigate"
          empty-message="No organizations have been added to this account."
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ListOrgs from '~/components/orgs/List'
import CreateOrg from '~/components/orgs/Upsert'
export default {
  components: {
    ListOrgs,
    CreateOrg
  },
  middleware: 'auth',
  data: () => ({
    //
  }),
  metaInfo () {
    return { title: this.$t('home') }
  },
  computed: {
    ...mapGetters({
      orgs: 'org/orgs'
    })
  }
}
</script>
