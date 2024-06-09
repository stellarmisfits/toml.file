<template>
  <div>
    <template v-if="action==='create'">
      <button
        class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
        @click.prevent="open=!open"
      >
        <a>New Principal</a>
      </button>
    </template>

    <template v-if="action==='update'">
      <fa
        icon="edit"
        class="hover:text-gray-400 cursor-pointer"
        @click.prevent="open=!open"
      />
    </template>

    <Modal :open="open">
      <div slot="title">
        {{ title }}
      </div>
      <div slot="subtitle">
        These fields go in the stellar.toml [[PRINCIPALS]] list. It contains identifying information for the primary point of contact or principal(s) of the organization.
      </div>
      <form slot="body" class="spaced-y-4" @submit.prevent="create" @keydown="form.onKeydown($event)">
        <alert-error :form="form" />

        <!-- name -->
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
          <has-error :form="form" field="alias" />
        </label>

        <!-- email -->
        <label class="block">
          <span class="form-label">Email</span>
          <input
            v-model="form.email"
            :class="{ 'is-invalid': form.errors.has('email') }"
            type="text"
            name="email"
            required="required"
            class="form-input mt-1"
          >
          <has-error :form="form" field="email" />
        </label>

        <!-- keybase -->
        <label class="block">
          <span class="form-label">Keybase</span>
          <input
            v-model="form.keybase"
            :class="{ 'is-invalid': form.errors.has('keybase') }"
            type="text"
            name="keybase"
            class="form-input mt-1"
          >
          <has-error :form="form" field="keybase" />
        </label>

        <!-- telegram -->
        <label class="block">
          <span class="form-label">Telegram</span>
          <input
            v-model="form.telegram"
            :class="{ 'is-invalid': form.errors.has('telegram') }"
            type="text"
            name="telegram"
            class="form-input mt-1"
          >
          <has-error :form="form" field="telegram" />
        </label>

        <!-- twitter -->
        <label class="block">
          <span class="form-label">Twitter</span>
          <input
            v-model="form.twitter"
            :class="{ 'is-invalid': form.errors.has('twitter') }"
            type="text"
            name="twitter"
            class="form-input mt-1"
          >
          <has-error :form="form" field="twitter" />
        </label>

        <!-- github -->
        <label class="block">
          <span class="form-label">Github</span>
          <input
            v-model="form.github"
            :class="{ 'is-invalid': form.errors.has('github') }"
            type="text"
            name="github"
            class="form-input mt-1"
          >
          <has-error :form="form" field="github" />
        </label>

        <!-- id_photo_hash -->
        <label class="block">
          <span class="form-label">ID Photo Hash</span>
          <input
            v-model="form.id_photo_hash"
            :class="{ 'is-invalid': form.errors.has('id_photo_hash') }"
            type="text"
            name="id_photo_hash"
            class="form-input mt-1"
          >
          <has-error :form="form" field="id_photo_hash" />
        </label>

        <!-- verification_photo_hash -->
        <label class="block">
          <span class="form-label">Verification Photo Hash</span>
          <input
            v-model="form.verification_photo_hash"
            :class="{ 'is-invalid': form.errors.has('verification_photo_hash') }"
            type="text"
            name="verification_photo_hash"
            class="form-input mt-1"
          >
          <has-error :form="form" field="verification_photo_hash" />
        </label>
      </form>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="open=false">
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
export default {
  props: {
    action: {
      type: String,
      required: true,
      validator: (val) => ['create', 'update'].includes(val)
    },
    principal: { type: Object, default: null }
  },
  data: () => ({
    open: false,
    form: new Form({
      name: '',
      email: '',
      keybase: '',
      telegram: '',
      twitter: '',
      github: '',
      id_photo_hash: '',
      verification_photo_hash: ''
    })
  }),
  computed: {
    title () {
      return (this.action === 'create') ? 'Add New Principal' : 'Update Principal'
    }
  },
  watch: {
    open: function () {
      // if in edit mode repopulate the current values every time the modal opens
      if (this.open && this.action === 'update') {
        this.form.name = this.principal.name
        this.form.email = this.principal.email
        this.form.keybase = this.principal.keybase
        this.form.telegram = this.principal.telegram
        this.form.twitter = this.principal.twitter
        this.form.github = this.principal.github
        this.form.id_photo_hash = this.principal.id_photo_hash
        this.form.verification_photo_hash = this.principal.verification_photo_hash
      }
    }
  },
  methods: {
    async save () {
      try {
        let data = {}
        if (this.action === 'create') {
          data = await this.form.post('/api/principals')
        }

        if (this.action === 'update') {
          data = await this.form.patch('/api/principals/' + this.$route.params.uuid)
        }

        this.$store.dispatch('principal/fetchPrincipals', { principal: data.data })
        this.form.reset()
        this.open = false
      } catch {

      }
    }
  }
}
</script>
