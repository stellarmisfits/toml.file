<template>
  <div>
    <a href="#" class="dropdown-item" @click.prevent="modal=true">
      <fa class="mr-2" icon="edit" />Update
    </a>
    <Modal :open="modal" @close="close">
      <div slot="title">
        Upload a logo for this {{ modelType }}.
      </div>
      <div slot="body">
        <input id="file" ref="file" type="file">
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="close">
          Cancel
        </button>
        <button type="button" class="relative ml-4 btn btn-black transition-all" @click="save">
          Upload
        </button>
      </div>
    </Modal>
  </div>
</template>
<script>
import Form from 'vform'
import Vapor from 'laravel-vapor'
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
      image_uuid: '',
      filename: '',
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
      try {
        const response = await Vapor.store(this.$refs.file.files[0], {
          progress: progress => {
            this.uploadProgress = Math.round(progress * 100)
          }
        })

        this.form.image_uuid = response.uuid
        this.form.content_type = this.$refs.file.files[0].type
        this.form.filename = this.$refs.file.files[0].name

        const { data } = await this.form.post('/api/image')

        if (this.modelType === 'asset') {
          this.$store.commit('asset/SET_ASSET', { asset: data.data })
        }

        if (this.modelType === 'organization') {
          this.$store.commit('org/SET_ORG', { org: data.data })
        }

        this.form.reset()
        this.close()
      } catch (e) {
        console.log(e)
        throw e
      }
    }
  }
}
</script>
