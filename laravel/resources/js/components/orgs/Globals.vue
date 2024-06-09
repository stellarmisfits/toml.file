<template>
  <div>
    <div class="flex -mx-4">
      <div class="w-1/3 px-2">
        <h3 class="text-md text-light-heading">
          Servers
        </h3>
        <div class="mt-4 text-light-secondary">
          The Federation Server definition is used for clients to resolve stellar addresses for users on your domain via <a target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0002.md">SEP-2</a> Federation Protocol
        </div>
        <div class="mt-4 text-light-secondary">
          The Auth Server definition is used for <a target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0003.md">SEP-3</a> Compliance Protocol
        </div>
        <div class="mt-4 text-light-secondary">
          The Transfer Server definition is used for <a target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0006.md">SEP-6</a> Anchor/Client interoperability
        </div>
        <div class="mt-4 text-light-secondary">
          The KYC Server definition is used for <a target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0012.md">SEP-12</a> Anchor/Client customer info transfer.
        </div>
        <div class="mt-4 text-light-secondary">
          The Web Auth Endpoint definition is used for <a target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0010.md">SEP-10</a> web authentication.
        </div>
        <div class="mt-4 text-light-secondary">
          The Horizon URL definition is the Location of public-facing Horizon instance (if you offer one).
        </div>
      </div>
      <div class="w-2/3 px-2">
        <a-well class="px-6 py-4">
          <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
            <!-- Federation Server -->
            <label class="block">
              <span class="form-label">Federation Server</span>
              <input v-model="form.federation_server" class="form-input" :class="{ 'is-invalid': form.errors.has('federation_server') }">
              <has-error :form="form" field="federation_server" />
            </label>

            <!-- Auth Server -->
            <label class="block">
              <span class="form-label">Auth Server</span>
              <input v-model="form.auth_server" class="form-input" :class="{ 'is-invalid': form.errors.has('auth_server') }">
              <has-error :form="form" field="auth_server" />
            </label>

            <!-- Transfer Server -->
            <label class="block">
              <span class="form-label">Transfer Server</span>
              <input v-model="form.transfer_server" class="form-input" :class="{ 'is-invalid': form.errors.has('transfer_server') }">
              <has-error :form="form" field="transfer_server" />
            </label>

            <!-- KYC Server -->
            <label class="block">
              <span class="form-label">KYC Server</span>
              <input v-model="form.kyc_server" class="form-input" :class="{ 'is-invalid': form.errors.has('kyc_server') }">
              <has-error :form="form" field="kyc_server" />
            </label>

            <!-- Web Auth Endpoint -->
            <label class="block">
              <span class="form-label">Web Auth Endpoint</span>
              <input v-model="form.web_auth_endpoint" class="form-input" :class="{ 'is-invalid': form.errors.has('web_auth_endpoint') }">
              <has-error :form="form" field="web_auth_endpoint" />
            </label>

            <!-- >Horizon Url-->
            <label class="block">
              <span class="form-label">Horizon Url</span>
              <input v-model="form.horizon_url" class="form-input" :class="{ 'is-invalid': form.errors.has('horizon_url') }">
              <has-error :form="form" field="horizon_url" />
            </label>

            <!-- Save Button -->
            <div class="text-right">
              <a-button
                :loading="form.busy"
                type="white"
                class="btn-white"
              >
                Save
              </a-button>
            </div>
          </form>
        </a-well>
      </div>
    </div>

    <hr class="my-10 border-b border-gray-800">

    <div class="flex -mx-4">
      <div class="w-1/3 px-2">
        <h3 class="text-light-heading">
          Signing Keys
        </h3>
        <div class="mt-4 text-light-secondary">
          The signing key is used for <a target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0003.md">SEP-3</a> Compliance Protocol
        </div>
        <div class="mt-4 text-light-secondary">
          The uri request signing key is used for <a target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0007.md">SEP-7</a> delegated signing.
        </div>
      </div>
      <div class="w-2/3 px-2">
        <a-well class="px-6 py-4">
          <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
            <!-- Signing Key -->
            <label class="block">
              <span class="form-label">Signing Key</span>
              <input v-model="form.signing_key_uuid" class="form-input" :class="{ 'is-invalid': form.errors.has('signing_key_uuid') }">
              <has-error :form="form" field="signing_key_uuid" />
            </label>

            <!-- Uri Request Signing Key -->
            <label class="block">
              <span class="form-label">Uri Request Signing Key</span>
              <input v-model="form.uri_request_signing_key_uuid" class="form-input" :class="{ 'is-invalid': form.errors.has('uri_request_signing_key_uuid') }">
              <has-error :form="form" field="uri_request_signing_key_uuid" />
            </label>

            <!-- Save Button -->
            <div class="text-right">
              <a-button
                :loading="form.busy"
                type="white"
                class="btn-white"
              >
                Save
              </a-button>
            </div>
          </form>
        </a-well>
      </div>
    </div>
  </div>
</template>
<script>
import Form from 'vform'
import AWell from '~/components/Well'
export default {
  components: { AWell },
  props: {
    organization: { type: Object, required: true }
  },
  data: () => ({
    form: new Form({
      federation_server: null,
      auth_server: null,
      transfer_server: null,
      kyc_server: null,
      web_auth_endpoint: null,
      horizon_url: null,
      signing_key_uuid: null,
      uri_request_signing_key_uuid: null
    })
  }),
  created () {
    this.form.federation_server = this.organization.federation_server
    this.form.auth_server = this.organization.auth_server
    this.form.transfer_server = this.organization.transfer_server
    this.form.kyc_server = this.organization.kyc_server
    this.form.web_auth_endpoint = this.organization.web_auth_endpoint
    this.form.horizon_url = this.organization.horizon_url
    this.form.signing_key_uuid = this.organization.signing_key_uuid
    this.form.uri_request_signing_key_uuid = this.organization.uri_request_signing_key_uuid
  },
  methods: {
    async update () {
      try {
        const { data } = await this.form.patch('/api/organizations/' + this.$route.params.uuid + '/globals')
        this.$store.commit('org/SET_ORG', { org: data.data })
        this.modal = false
      } catch (e) {
        throw e
      }
    }
  }
}
</script>
