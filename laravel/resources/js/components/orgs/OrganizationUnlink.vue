<template>
  <div>
    <fa icon="times-circle" class="hover:text-gray-400 cursor-pointer" @click.prevent="modal=!modal" />
    <Modal :open="modal">
      <div slot="title">
        Are you sure you want to unlink {{ resourceType }}?
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="modal=false">
          Cancel
        </button>
        <button type="button" class="relative ml-4 btn btn-black transition-all" @click="save">
          Unlink
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
    resourceUuid: { type: String, required: true },
    resourceType: {
      type: String,
      required: true,
      validator: (val) => ['asset', 'account', 'principal', 'validator'].includes(val)
    }
  },
  data: () => ({
    modal: false,
    form: new Form({
      resource_uuid: '',
      resource_type: ''
    })
  }),
  created () {
    this.form.resource_uuid = this.resourceUuid
    this.form.resource_type = this.resourceType
  },
  methods: {
    async save () {
      await this.form.delete('/api/organizations/' + this.org.uuid + '/link')
      this.$emit('organizationUnlinked', this.resourceType)
      this.form.reset()
      this.modal = false
    }
  }
}
</script>
