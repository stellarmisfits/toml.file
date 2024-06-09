<template>
  <div>
    <div class="flex -mx-4">
      <div class="w-1/3 px-2">
        <h3 class="text-md font-semibold text-light-heading">
          Address & Phone Number
        </h3>
        <div class="mt-4 text-sm text-light-secondary">
          The phone number must be provided in a valid E.164 format, e.g. +14155552671.
        </div>
        <div class="mt-4 text-sm text-light-secondary">
          Attestations are links to an image or pdf showing both the address or phone number and your organization's name. Only documents from an official third party are acceptable. E.g. a utility bill, mail from a financial institution, or business license.
        </div>
      </div>
      <div class="w-2/3 px-2">
        <a-well class="px-6 py-4">
          <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
            <!-- Address -->
            <label class="block">
              <span class="form-label">Address</span>
              <input v-model="form.address" class="form-input" :class="{ 'is-invalid': form.errors.has('address') }">
              <has-error :form="form" field="address" />
            </label>

            <!-- Phone -->
            <label class="block">
              <span class="form-label">Phone</span>
              <input v-model="form.phone" class="form-input" :class="{ 'is-invalid': form.errors.has('phone') }">
              <has-error :form="form" field="phone" />
            </label>

            <!-- Address Attestation -->
            <label class="block">
              <span class="form-label">Address Attestation</span>
              <input v-model="form.address_attestation" class="form-input" :class="{ 'is-invalid': form.errors.has('address_attestation') }">
              <has-error :form="form" field="address_attestation" />
            </label>

            <!-- Phone Attestation -->
            <label class="block">
              <span class="form-label">Phone Attestation</span>
              <input v-model="form.phone_attestation" class="form-input" :class="{ 'is-invalid': form.errors.has('phone_attestation') }">
              <has-error :form="form" field="phone_attestation" />
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
        <h3 class="text-md font-semibold text-light-heading">
          Contact Information
        </h3>
        <div class="mt-2 text-sm text-light-secondary">
          Note that the given keybase account should contain proof of ownership
          of any public online accounts you list here, including your organization's
          domain.
        </div>
        <div class="mt-2 text-sm text-light-secondary">
          Additionally the given email account must be hosted at the ORG_URL domain.
        </div>
      </div>
      <div class="w-2/3 px-2">
        <a-well class="px-6 py-4">
          <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
            <!-- Keybase -->
            <label class="block">
              <span class="form-label">Keybase</span>
              <input v-model="form.keybase" class="form-input" :class="{ 'is-invalid': form.errors.has('keybase') }">
              <has-error :form="form" field="keybase" />
            </label>

            <!-- twitter -->
            <label class="block">
              <span class="form-label">Twitter</span>
              <input v-model="form.twitter" class="form-input" :class="{ 'is-invalid': form.errors.has('twitter') }">
              <has-error :form="form" field="twitter" />
            </label>

            <!-- github -->
            <label class="block">
              <span class="form-label">Github</span>
              <input v-model="form.github" class="form-input" :class="{ 'is-invalid': form.errors.has('github') }">
              <has-error :form="form" field="github" />
            </label>

            <!-- email -->
            <label class="block">
              <span class="form-label">Email</span>
              <input v-model="form.email" class="form-input" :class="{ 'is-invalid': form.errors.has('email') }">
              <has-error :form="form" field="email" />
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
        <h3 class="text-md font-semibold text-light-heading">
          Licensing Information
        </h3>
        <div class="mt-2 text-sm text-light-secondary">
          If applicable enter the name of the authority or agency that licensed
          your organization, the type of financial or other license your
          organization holds, and the official license number of your organization.
        </div>
      </div>
      <div class="w-2/3 px-2">
        <a-well class="px-6 py-4">
          <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
            <!-- licensing_authority -->
            <label class="block">
              <span class="form-label">Licensing Authority</span>
              <input v-model="form.licensing_authority" class="form-input" :class="{ 'is-invalid': form.errors.has('licensing_authority') }">
              <has-error :form="form" field="licensing_authority" />
            </label>

            <!-- license_type -->
            <label class="block">
              <span class="form-label">License Type</span>
              <input v-model="form.license_type" class="form-input" :class="{ 'is-invalid': form.errors.has('license_type') }">
              <has-error :form="form" field="license_type" />
            </label>

            <!-- license_number -->
            <label class="block">
              <span class="form-label">License Number</span>
              <input v-model="form.license_number" class="form-input" :class="{ 'is-invalid': form.errors.has('license_number') }">
              <has-error :form="form" field="license_number" />
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
// import Places from '~/components/Places'
export default {
  components: {
    AWell
    // Places
  },
  props: {
    organization: { type: Object, required: true }
  },
  data: () => ({
    form: new Form({
      address: null,
      address_attestation: null,
      phone: null,
      phone_attestation: null,
      keybase: null,
      twitter: null,
      github: null,
      email: null,
      licensing_authority: null,
      license_type: null,
      license_number: null
    })
  }),
  created () {
    this.form.address = this.organization.address
    this.form.address_attestation = this.organization.address_attestation
    this.form.phone = this.organization.phone
    this.form.phone_attestation = this.organization.phone_attestation
    this.form.keybase = this.organization.keybase
    this.form.twitter = this.organization.twitter
    this.form.github = this.organization.github
    this.form.email = this.organization.email
    this.form.licensing_authority = this.organization.licensing_authority
    this.form.license_type = this.organization.license_type
    this.form.license_number = this.organization.license_number
  },
  methods: {
    async update () {
      try {
        const { data } = await this.form.patch('/api/organizations/' + this.$route.params.uuid + '/documentation')
        this.$store.commit('org/SET_ORG', { org: data.data })
        this.modal = false
      } catch (e) {
        throw e
      }
    }
  }
}
</script>
