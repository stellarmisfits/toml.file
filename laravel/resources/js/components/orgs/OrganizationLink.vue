<template>
  <div>
    <button
      class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
      @click.prevent="modal=!modal"
    >
      <span>Link Organization</span>
    </button>
    <Modal :open="modal">
      <div v-if="unlinkedOrgs.length" slot="title">
        Link {{ resourceType }} to an organization.
      </div>
      <div slot="body">
        <form v-if="unlinkedOrgs.length" class="spaced-y-4">
          <label class="block">
            <select v-model="orgUuid" :class="{ 'is-invalid': form.errors.has('resource_uuid') }" class="form-select mt-1">
              <option disabled :value="null">Select Organization...</option>
              <option
                v-for="org in unlinkedOrgs"
                :key="org.uuid"
                :value="org.uuid"
              >
                {{ org.name }}
              </option>
            </select>
            <has-error :form="form" field="resource_uuid" />
          </label>
        </form>
        <a-empty-list v-else>
          No unlinked organizations were found. To create a new one
          <router-link :to="{ name: 'dashboard' }">
            click here.
          </router-link>
        </a-empty-list>
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="modal=false">
          Cancel
        </button>
        <button type="button" :disabled="!orgUuid" class="relative ml-4 btn btn-black transition-all" @click="save">
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
    unlinkedOrgs: { type: Array, required: true },
    resourceUuid: { type: String, required: true },
    resourceType: {
      type: String,
      required: true,
      validator: (val) => ['asset', 'account', 'principal', 'validator'].includes(val)
    }
  },
  data: () => ({
    modal: false,
    orgUuid: null,
    form: new Form({
      resource_type: '',
      resource_uuid: ''
    })
  }),
  created () {
    this.form.resource_type = this.resourceType
    this.form.resource_uuid = this.resourceUuid
  },
  methods: {
    async save () {
      await this.form.post('/api/organizations/' + this.orgUuid + '/link')

      this.$emit('organizationLinked', this.resourceType)
      this.form.reset()
      this.modal = false
    }
  }
}
</script>
