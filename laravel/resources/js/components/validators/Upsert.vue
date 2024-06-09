<template>
  <div>
    <template v-if="action==='create'">
      <button
        class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
        @click.prevent="open=!open"
      >
        <a>Add Validator</a>
      </button>
    </template>

    <template v-if="action==='update'">
      <button
        class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
        @click.prevent="open=!open"
      >
        <a>Edit Validator</a>
      </button>
    </template>

    <Modal :open="open">
      <div slot="title">
        New Validator
      </div>
      <div slot="subtitle">
        Complete all applicable fields and exclude any that don't apply.
      </div>
      <div slot="body">
        <form class="spaced-y-4" @submit.prevent="create" @keydown="form.onKeydown($event)">
          <alert-error :form="form" />
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
            <span class="form-label">Host</span>
            <input
              v-model="form.host"
              :class="{ 'is-invalid': form.errors.has('host') }"
              type="text"
              name="host"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="host" />
          </label>
          <label class="block">
            <span class="form-label">History</span>
            <input
              v-model="form.history"
              :class="{ 'is-invalid': form.errors.has('history') }"
              type="text"
              name="history"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="history" />
          </label>
        </form>
      </div>
      <div slot="actions">
        <button
          class="btn btn-white transition-all"
          @click="open=false"
        >
          Cancel
        </button>
        <a-button
          :loading="form.busy"
          type="white"
          class="relative ml-4 btn transition-all"
          @click="save"
        >
          Save
        </a-button>
      </div>
    </Modal>
  </div>
</template>
<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
export default {
  props: {
    action: {
      type: String,
      required: true,
      validator: (val) => ['create', 'update'].includes(val)
    },
    validator: { type: Object, default: null }
  },
  data: () => ({
    open: false,
    form: new Form({
      name: '',
      alias: '',
      account_uuid: null,
      host: '',
      history: ''
    })
  }),
  computed: {
    ...mapGetters({
      accounts: 'account/accounts'
    })
  },
  watch: {
    open: function () {
      // if in edit mode repopulate the current values every time the modal opens
      if (this.open && this.action === 'update') {
        this.form.alias = this.validator.alias
        this.form.name = this.validator.name
        this.form.account_uuid = this.validator.account_uuid
        this.form.host = this.validator.host
        this.form.history = this.validator.history
      }
    }
  },
  created () {
    this.$store.dispatch('account/fetchAccounts')
  },
  methods: {
    async save () {
      try {
        let data = {}
        if (this.action === 'create') {
          data = await this.form.post('/api/validators')
        }

        if (this.action === 'update') {
          data = await this.form.patch('/api/validators/' + this.$route.params.uuid)
        }

        this.$store.dispatch('validator/fetchValidators', { principal: data.data })
        this.form.reset()
        this.open = false
      } catch {

      }
    }
  }
}
</script>
