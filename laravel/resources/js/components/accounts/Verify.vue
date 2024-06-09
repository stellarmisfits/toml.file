<template>
  <div>
    <alert-success :form="form" message="The account has been successfully verified!" />
    <div v-if="!account.verified">
      <div>
        To verify this account you must submit a challenge transaction signed with
        the account's private key. This process loosely follows the
        <a class="hover:text-gray-500" target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0010.md">SEP: 0010 protocol <fa class="text-xs mb-1" icon="external-link-alt" /></a>.
      </div>
      <hr class="my-8 border-b">
      <div class="">
        <div v-if="challenge" class="spaced-y-6">
          <label class="block">
            <div class="flex justify-between">
              <span class="form-label block">Challenge Transaction</span>
              <a :href="transactionSignerUrl" target="_blank" class="form-label block hover:text-gray-500 ">Open in stellar.org's transaction signer <fa class="text-xs" icon="external-link-alt" /></a>
            </div>
            <textarea
              :value="challenge"
              disabled
              class="form-textarea mt-1"
              rows="6"
            />
          </label>
          <label class="block">
            <span class="form-label">Paste Signed Transactions Here:</span>
            <textarea
              v-model="form.transaction"
              :class="{ 'is-invalid': form.errors.has('transaction') }"
              class="form-textarea mt-1"
              rows="6"
            />
            <has-error :form="form" field="transaction" />
          </label>
          <div class="flex justify-center">
            <a-button :loading="form.busy" class="block w-1/2" @click="save">
              verify
            </a-button>
          </div>
        </div>
        <div v-else class="flex justify-center">
          <a-button :loading="challengeForm.busy" class="block w-1/2" type="white" @click="request">
            Request Challenge
          </a-button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
export default {
  props: {
    account: { type: Object, required: true },
    organization: { type: Object, default: null }
  },

  data: () => ({
    form: new Form({
      transaction: null
    }),
    challengeForm: new Form()
  }),

  computed: {
    ...mapGetters({
      challenge: 'account/challenge'
    }),

    transactionSignerUrl () {
      return 'https://www.stellar.org/laboratory/#txsigner?xdr=' + encodeURIComponent(this.challenge)
    }
  },

  created () {
    //
  },

  methods: {
    async save () {
      const { data } = await this.form.post('/api/accounts/' + this.account.uuid + '/verify')
      this.$store.commit('account/SET_ACCOUNT', { account: data.data })
    },
    async request () {
      const { data } = await this.challengeForm.get('/api/accounts/' + this.account.uuid + '/verify')
      this.$store.commit('account/SET_CHALLENGE', { challenge: data })
    }
  }
}
</script>
