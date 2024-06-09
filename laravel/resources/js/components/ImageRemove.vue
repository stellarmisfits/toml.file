<template>
  <div>
    <a href="#" class="dropdown-item" @click.prevent="modal=true">
      <fa class="mr-2" icon="times-circle" />Remove
    </a>
    <Modal :open="modal" @close="close">
      <div slot="title">
        Are you sure you want to delete the logo for this {{ modelType }}?
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="close">
          Cancel
        </button>
        <button type="button" class="relative ml-4 btn btn-black transition-all" @click="save">
          Delete
        </button>
      </div>
    </Modal>
  </div>
</template>
<script>
import Form from 'vform'
export default {
  props: {
    modelUuid: { type: String, required: true },
    modelType: {
      type: String,
      required: true,
      validator: (val) => ['asset', 'organization'].includes(val)
    }
  },
  data: () => ({
    modal: false,
    form: new Form({
      model_uuid: '',
      model_type: '',
      collection: 'logo'
    })
  }),
  created () {
    this.form.model_uuid = this.modelUuid
    this.form.model_type = this.modelType
  },
  methods: {
    close () {
      this.modal = false
      this.$emit('close')
    },
    async save () {
      const { data } = await this.form.delete('/api/image')

      if (this.modelType === 'asset') {
        this.$store.commit('asset/SET_ASSET', { asset: data.data })
      }

      if (this.modelType === 'organization') {
        this.$store.commit('org/SET_ORG', { org: data.data })
      }

      this.form.reset()
      this.close()
    }
  }
}
</script>
