<template>
  <div class="flex-grow">
    <a-breadcrumbs>
      <span slot="title">Accounts</span>
    </a-breadcrumbs>
    <div class="px-12 py-8 mx-auto max-w-4xl">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading text-lg font-bold">
            Accounts
          </h2>
          <div class="mt-1 text-light text-sm text-light-secondary">
            All of the Stellar accounts tied to your user account are listed below.
          </div>
        </div>
        <div class="flex-shrink-0 ml-4">
          <AccountCreate
            action="create"
          />
        </div>
      </div>
      <div class="mt-4">
        <AccountList
          :accounts="accounts"
          empty-message="No account records have been added."
          action="navigate"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import AccountList from '~/components/accounts/List'
import AccountCreate from '~/components/accounts/Upsert'
export default {
  middleware: 'auth',

  components: {
    AccountList,
    AccountCreate
  },
  data () {
    return {
      //
    }
  },
  computed: {
    ...mapGetters('account', ['accounts'])
  },
  created () {
    this.$store.dispatch('account/fetchAccounts')
  }
}
</script>
