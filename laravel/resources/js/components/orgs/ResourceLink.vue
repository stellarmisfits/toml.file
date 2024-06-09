<template>
  <div>
    <button
      class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
      @click.prevent="modal=!modal"
    >
      <span>Link {{ resourceType }}</span>
    </button>
    <Modal :open="modal">
      <div slot="title">
        Link {{ resourceType }}
      </div>
      <div slot="body">
        <form v-if="unlinkedResources.length" class="spaced-y-4">
          <label class="block">
            <select
              v-model="form.resource_uuid"
              :class="{ 'is-invalid': form.errors.has('resource_uuid') }"
              class="form-select mt-1"
            >
              <option disabled :value="null">Select {{ resourceType }}...</option>
              <option
                v-for="resource in unlinkedResources"
                :key="resource.uuid"
                :value="resource.uuid"
              >
                {{ resource.name }}
              </option>
            </select>
            <has-error :form="form" field="email" />
          </label>
        </form>
        <a-empty-list v-else>
          No unlinked {{ resourceType }} records were found. To create a new {{ resourceType }}
          <router-link :to="'/app/' + resourceType + 's'">
            click here.
          </router-link>
        </a-empty-list>
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="modal=false">
          Cancel
        </button>
        <button type="button" :disabled="!form.resource_uuid" class="relative ml-4 btn btn-black transition-all" @click="save">
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
    org: { type: Object, required: true },
    unlinkedResources: {
      type: Array,
      required: true
    },
    resourceType: {
      type: String,
      required: true,
      validator: (val) => ['asset', 'account', 'validator', 'principal'].includes(val)
    }
  },
  data: () => ({
    modal: false,
    form: new Form({
      resource_type: null,
      resource_uuid: null
    })
  }),
  created () {
    this.form.resource_type = this.resourceType
  },
  methods: {
    async save () {
      await this.form.post('/api/organizations/' + this.org.uuid + '/link')
      this.$emit('success', this.resourceType)
      this.form.reset()
      this.modal = false
    }
  }
}
</script>
