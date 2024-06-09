<template>
  <div>
    <template v-if="action==='create'">
      <button
        class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
        @click.prevent="modal=!modal"
      >
        New Organization
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
      <div slot="body">
        <form class="spaced-y-6" @submit.prevent="update" @keydown="form.onKeydown($event)">
          <label class="block">
            <span class="form-label">Name</span>
            <span class="form-label-subtext">The name of your organization. Between 4 and 53 characters</span>
            <input
              v-model="form.name"
              :class="{ 'is-invalid': form.errors.has('name') }"
              type="text"
              name="name"
              required="required"
              class="form-input mt-1"
            >
            <span class="form-label-subtext">* required</span>
            <has-error :form="form" field="name" />
          </label>
          <label class="block">
            <span class="form-label">Alias</span>
            <span class="form-label-subtext">
              Between 4 and 15 characters. Must be unique across all organizations
              maintained at astrify.com.
            </span>
            <input
              v-model="form.alias"
              :class="{ 'is-invalid': form.errors.has('alias') }"
              type="text"
              name="alias"
              required="required"
              class="form-input mt-1"
            >
            <span class="form-label-subtext">* required</span>
            <has-error :form="form" field="alias" />
          </label>
          <template v-if="action==='update'">
            <label class="block">
              <span class="form-label">Description</span>
              <span class="form-label-subtext">
                Short description of your organization.
              </span>
              <textarea
                v-model="form.description"
                :class="{ 'is-invalid': form.errors.has('description') }"
                class="form-textarea mt-1"
                rows="3"
              />
              <has-error :form="form" field="description" />
            </label>
            <label class="block">
              <span class="form-label">Home Domain</span>
              <span class="form-label-subtext">
                Your organization's official URL. If omitted a subdomain of
                astrify.com matching the organization's alias will be
                used instead.
              </span>
              <input
                v-model="form.custom_url"
                :class="{ 'is-invalid': form.errors.has('custom_url') }"
                type="text"
                name="custom_url"
                class="form-input mt-1"
              >
              <has-error :form="form" field="custom_url" />
            </label>
            <label class="block">
              <span class="form-label">DBA</span>
              <span class="form-label-subtext">
                The operating name of the organization (if different from the given name).
              </span>
              <input
                v-model="form.dba"
                :class="{ 'is-invalid': form.errors.has('dba') }"
                type="text"
                name="dba"
                class="form-input mt-1"
              >
              <has-error :form="form" field="dba" />
            </label>
          </template>
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
    organization: { type: Object, default: null }
  },
  data: () => ({
    modal: false,
    form: new Form({
      name: null,
      alias: null,
      custom_url: null,
      dba: null,
      description: null
    })
  }),
  computed: {
    title () {
      return (this.action === 'create') ? 'Add New Organization' : 'Update Organization'
    }
  },
  watch: {
    modal: function () {
      if (this.modal && this.action === 'update') {
        this.form.name = this.organization.name
        this.form.alias = this.organization.alias
        this.form.custom_url = this.organization.custom_url
        this.form.dba = this.organization.dba
        this.form.description = this.organization.description
      }
    }
  },
  methods: {
    async save () {
      try {
        if (this.action === 'create') {
          const { data } = await this.form.post('/api/organizations')
          this.$store.commit('org/SET_ORG', { org: data.data })
        }

        if (this.action === 'update') {
          const { data } = await this.form.patch('/api/organizations/' + this.$route.params.uuid)
          this.$store.commit('org/SET_ORG', { org: data.data })
        }

        this.form.reset()
        this.modal = false
      } catch {

      }
    }
  }
}
</script>
