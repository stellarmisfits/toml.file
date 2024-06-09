<template>
  <div>
    <a-well class="p-6">
      <h3 class="text-md text-dark-heading">
        Publish TOML
      </h3>
      <div class="mt-4 text-sm text-dark">
        Once published your organization's TOML file will be available at the
        location below. If you've selected a custom domain for your
        organization you should add a CNAME record pointing to the astrify
        subdomain.
      </div>
      <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
        <div class="mt-4">
          <label class="inline-flex items-center">
            <input
              v-model="form.published"
              :value="true"
              type="radio"
              class="form-radio"
              name="published"
            >
            <span class="ml-2">Published</span>
          </label>
          <label class="inline-flex items-center ml-6">
            <input
              v-model="form.published"
              :value="false"
              type="radio"
              class="form-radio"
              name="published"
            >
            <span class="ml-2">Unpublished</span>
          </label>
        </div>
      </form>
      <div class="mt-4 text-sm text-gray-800">
        <div class="font-mono text-sm bg-gray-800 text-white p-2 rounded flex justify-between">
          <div>{{ organization.hosted_url }}/.well-known/stellar.toml</div>
          <a v-if="published" class="block mr-2" target="_blank" :href="`https://${organization.hosted_url}/.well-known/stellar.toml`">
            <fa icon="external-link-alt" class="hover:text-gray-400 cursor-pointer" />
          </a>
        </div>
      </div>
    </a-well>
    <Modal :open="modal">
      <div slot="title">
        Are you sure you want to <span>{{ (published) ? 'publish' : 'un-publish' }}</span>  this organization?
      </div>
      <div slot="subtitle">
        <span>Once published the TOML file will be publicly available at https://{{ organization.hosted_url }}/.well-known/stellar.toml</span>
        <alert-error :form="form" />
        <has-error class="mt-2" :form="form" field="organization_uuid" />
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="close">
          Cancel
        </button>
        <button type="button" class="relative ml-4 btn btn-black transition-all" @click="save">
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
    organization: { type: Object, default: null }
  },
  data: () => ({
    modal: false,
    form: new Form({
      published: null
    })
  }),
  computed: {
    published () {
      return this.form.published
    },
    hostedUrl () {
      return this.organization.hosted_url + '/.well-known/stellar.toml'
    }
  },
  watch: {
    published: function (newVal, oldVal) {
      if (this.form.originalData.published !== newVal) {
        this.modal = true
      }
    }
  },
  created () {
    let data = {}
    data.published = this.organization.published
    this.form = new Form(data)
  },
  methods: {
    async save () {
      if (this.form.published) {
        const { data } = await this.form.post('/api/organizations/' + this.organization.uuid + '/publish')
        this.$store.commit('org/SET_ORG', { org: data.data })
      } else {
        const { data } = await this.form.delete('/api/organizations/' + this.organization.uuid + '/publish')
        this.$store.commit('org/SET_ORG', { org: data.data })
      }

      this.modal = false
    },
    close () {
      this.form.reset()
      this.modal = false
    }
  }
}
</script>
