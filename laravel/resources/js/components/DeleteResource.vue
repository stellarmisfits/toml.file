<template>
  <div>
    <Dropdown>
      <span slot="link" class="flex items-center">
        <fa icon="times-circle" class="text-light-heading hover:text-gray-500 cursor-pointer" />
      </span>
      <div slot="dropdown" class="py-2 w-64">
        <a href="#" class="dropdown-item" @click.prevent="modal=true">
          Delete {{ resourceType.toLowerCase() }}
        </a>
      </div>
    </Dropdown>
    <Modal :open="modal">
      <div slot="title">
        Are you sure you want to delete this resource?
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="modal=false">
          Cancel
        </button>
        <button type="button" class="relative ml-4 btn btn-black transition-all" @click.prevent="save">
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
    resource: { type: Object, required: true },
    resourceType: {
      type: String,
      required: true,
      validator: (val) => ['ACCOUNT', 'ASSET', 'PRINCIPAL', 'VALIDATOR', 'ORGANIZATION'].includes(val)
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
    this.form.resource_uuid = this.resource.uuid
    this.form.resource_type = this.resourceType
  },
  methods: {
    async save () {
      const uuid = this.$route.params.uuid

      if (this.resourceType === 'ACCOUNT') {
        await this.form.delete('/api/accounts/' + uuid)
        this.$store.dispatch('account/fetchAccounts')
        this.$router.push({ name: 'accounts' })
      }

      if (this.resourceType === 'ASSET') {
        await this.form.delete('/api/assets/' + uuid)
        this.$store.dispatch('asset/fetchAssets')
        this.$router.push({ name: 'assets' })
      }

      if (this.resourceType === 'PRINCIPAL') {
        await this.form.delete('/api/principals/' + uuid)
        this.$store.dispatch('principal/fetchPrincipals')
        this.$router.push({ name: 'principals' })
      }

      if (this.resourceType === 'VALIDATOR') {
        await this.form.delete('/api/validators/' + uuid)
        this.$store.dispatch('validator/fetchValidators')
        this.$router.push({ name: 'validators' })
      }

      if (this.resourceType === 'ORGANIZATION') {
        await this.form.delete('/api/organizations/' + uuid)
        this.$store.dispatch('org/fetchOrgs')
        this.$router.push({ name: 'dashboard' })
      }

      this.form.reset()
      this.modal = false
    }
  }
}
</script>
