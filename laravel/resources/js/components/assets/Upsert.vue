<template>
  <div>
    <template v-if="action==='create'">
      <button
        class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
        @click.prevent="modal=!modal"
      >
        Add Asset
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
        Add an alias and public key of a stellar asset that you control.
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
            <span class="form-label">Code</span>
            <span class="form-label-subtext">Uppercase. Either 4 or 12 characters.</span>
            <input
              v-model="form.code"
              :class="{ 'is-invalid': form.errors.has('code') }"
              type="text"
              name="code"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="code" />
          </label>
          <label class="block">
            <span class="form-label">Public Key</span>
            <select
              v-model="form.account_uuid"
              :class="{ 'is-invalid': form.errors.has('account_uuid') }"
              class="form-select mt-1"
              name="account_uuid"
            >
              <option :value="null">NONE</option>
              <option
                v-for="account in accounts"
                :key="account.uuid"
                :value="account.uuid"
              >
                {{ account.alias }}
              </option>
            </select>
            <has-error :form="form" field="account_uuid" />
          </label>
          <label class="block">
            <span class="form-label">Description</span>
            <textarea
              v-model="form.description"
              :class="{ 'is-invalid': form.errors.has('description') }"
              class="form-textarea mt-1"
              rows="3"
              name="description"
            />
            <has-error :form="form" field="description" />
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
import { mapGetters } from 'vuex'
import Form from 'vform'
export default {
  props: {
    action: {
      type: String,
      required: true,
      validator: (val) => ['create', 'update'].includes(val)
    },
    asset: { type: Object, default: null }
  },
  data: () => ({
    modal: false,
    form: new Form({
      name: null,
      code: null,
      account_uuid: null,
      description: null
    })
  }),
  computed: {
    ready: function () {
      return this.form.name && this.form.alias && this.form.public_key
    },
    ...mapGetters({
      accounts: 'account/accounts'
    }),
    title () {
      return (this.action === 'create') ? 'Add New Account' : 'Update Account'
    }
  },
  watch: {
    modal: function () {
      // if in edit mode repopulate the current values every time the modal opens
      if (this.modal && this.action === 'update') {
        this.form.name = this.asset.name
        this.form.code = this.asset.code
        this.form.account_uuid = this.asset.account_uuid
        this.form.description = this.asset.description
      }
    }
  },
  created () {
    this.$store.dispatch('account/fetchAccounts')
  },
  methods: {
    async save () {
      try {
        if (this.action === 'create') {
          const { data } = await this.form.post('/api/assets')
          this.$store.commit('asset/SET_ASSET', { asset: data.data })
        }

        if (this.action === 'update') {
          const { data } = await this.form.patch('/api/assets/' + this.$route.params.uuid)
          this.$store.commit('asset/SET_ASSET', { asset: data.data })
        }

        this.form.reset()
        this.modal = false
      } catch {

      }
    }
  }
}
</script>
