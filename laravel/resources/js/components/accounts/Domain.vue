<template>
  <div>
    <alert-success :form="form" message="The account has been successfully verified!" />
    <div v-if="!account.verified && organization">
      <div>
        To verify this account you must first set the account's home domain to
        the organization's url listed below. Once you've submitted the update to
        the stellar network click verify so astrify can confirm the change.
      </div>
      <div class="mt-4">
        <pre class="text-sm bg-gray-800 text-white p-2 rounded">{{ organization.url }}</pre>
        <alert-error :form="form" />
        <has-error class="mt-2" :form="form" field="account_uuid" />
      </div>
      <div class="mt-4 -mx-2 flex justify-end">
        <a class="block w-2/3 px-2 mr-4" :href="url" target="_blank">
          <a-button class="w-full" type="white">build verification transaction</a-button>
        </a>
        <a-button class="block w-1/3 px-2" @click="save">
          verify
        </a-button>
      </div>
    </div>
    <div v-if="!account.verified && !organization">
      <div>
        To verify this account you must first link it to an organization.
      </div>
    </div>
  </div>
</template>
<script>
import Form from 'vform'
export default {
  props: {
    account: { type: Object, required: true },
    organization: { type: Object, default: null }
  },

  data: () => ({
    modal: false,
    form: new Form({
      account_uuid: null
    })
  }),

  computed: {
    eligibilityMessage () {
      if (!this.organization) {
        return 'To verify this account you must first link it to an organization.'
      }

      return 'test'
    },

    url () {
      return 'https://www.stellar.org/laboratory/#txbuilder?params=' + this.labParamsBase64 + '&network=test'
    },

    labParams () {
      const homeDomain = (this.organization) ? this.organization.url : null
      return {
        'attributes': {
          'sourceAccount': this.account.public_key,
          'sequence': null,
          'fee': '100'
        },
        'operations': [
          {
            'id': 0,
            'attributes': {
              'homeDomain': homeDomain
            },
            'name': 'setOptions'
          }
        ]
      }
    },

    labParamsBase64 () {
      if (!this.labParams) return null

      const dataJsonStr = JSON.stringify(this.labParams)
      return Buffer.from(dataJsonStr).toString('base64')
    }
  },

  created () {
    //
  },

  methods: {
    async save () {
      this.form.account_uuid = this.account.uuid
      const { data } = await this.form.post('/api/accounts/' + this.account.uuid + '/verify')
      this.$store.commit('account/SET_ACCOUNT', { account: data.data })
      this.modal = false
    }
  }
}
</script>
