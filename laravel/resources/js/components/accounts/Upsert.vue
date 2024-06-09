<template>
  <div>
    <template v-if="action==='create'">
      <button
        class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
        @click.prevent="modal=!modal"
      >
        Add Account
      </button>
    </template>

    <template v-if="action==='update'">
      <fa
        icon="edit"
        class="hover:text-gray-400 cursor-pointer"
        @click.prevent="modal=!modal"
      />
    </template>

    <Modal :open="modal">
      <div slot="title">
        {{ title }}
      </div>
      <div slot="subtitle">
        Add an alias and public key of a stellar account that you control.
      </div>
      <div slot="body">
        <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
          <label class="block">
            <span class="form-label">Name</span>
            <input
              v-model="form.name"
              :class="{ 'is-invalid': form.errors.has('name') }"
              type="text"
              name="name"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="name" />
          </label>
          <label class="block">
            <span class="form-label">Alias</span>
            <input
              v-model="form.alias"
              :class="{ 'is-invalid': form.errors.has('alias') }"
              type="text"
              name="alias"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="alias" />
          </label>
          <label class="block">
            <span class="form-label">Public Key</span>
            <span v-if="verified" class="form-label-subtext">Note: changing the public key of a verified account will un-publish any organizations the account is tied to.</span>
            <input
              v-model="form.public_key"
              :class="{ 'is-invalid': form.errors.has('public_key') }"
              type="text"
              name="public_key"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="public_key" />
          </label>
        </form>
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="modal=false">
          Cancel
        </button>
        <button type="button" class="relative ml-4 btn btn-black transition-all" @click.prevent="save">
          Save
        </button>
      </div>
    </Modal>
  </div>
</template>
<script>
import Form from 'vform'
export default {
  props: {
    action: {
      type: String,
      required: true,
      validator: (val) => ['create', 'update'].includes(val)
    },
    account: { type: Object, default: null }
  },
  data: () => ({
    modal: false,
    form: new Form({
      name: null,
      alias: null,
      public_key: null
    })
  }),
  computed: {
    ready: function () {
      return this.form.name && this.form.alias && this.form.public_key
    },
    title () {
      return (this.action === 'create') ? 'Add New Account' : 'Update Account'
    },
    verified () {
      if (!this.account) {
        return false
      }

      return this.account.verified
    }
  },
  watch: {
    modal: function () {
      // if in edit mode repopulate the current values every time the modal opens
      if (this.modal && this.action === 'update') {
        this.form.name = this.account.name
        this.form.alias = this.account.alias
        this.form.public_key = this.account.public_key
      }
    }
  },
  methods: {
    async save () {
      try {
        if (this.action === 'create') {
          const { data } = await this.form.post('/api/accounts')
          this.$store.commit('account/SET_ACCOUNT', { account: data.data })
        }

        if (this.action === 'update') {
          const { data } = await this.form.patch('/api/accounts/' + this.$route.params.uuid)
          this.$store.commit('account/SET_ACCOUNT', { account: data.data })
          this.$store.dispatch('account/fetchLinkedOrgs', { resourceUuid: this.account.uuid, resourceType: 'accounts' })
        }

        this.form.reset()
        this.modal = false
      } catch {

      }
    }
  }
}
</script>
