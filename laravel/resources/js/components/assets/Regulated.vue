<template>
  <a-well>
    <a-well class="px-6 py-4">
      <form v-if="asset.regulated" class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
        <!-- approval_server -->
        <label class="block">
          <span class="form-label">Approval Server</span>
          <span class="form-label-subtext">Url of a <a class="underline hover:text-gray-500" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0008.md" target="_blank">SEP 0008</a> compliant approval service that signs validated transactions..</span>
          <input v-model="form.approval_server" class="form-input" :class="{ 'is-invalid': form.errors.has('approval_server') }">
          <has-error :form="form" field="approval_server" />
        </label>

        <!-- approval_criteria -->
        <label class="block">
          <span class="form-label">Approval Criteria</span>
          <span class="form-label-subtext">A human readable string that explains the issuer's requirements for approving transactions.</span>
          <textarea v-model="form.approval_criteria" rows="3" class="form-textarea" :class="{ 'is-invalid': form.errors.has('approval_criteria') }" />
          <has-error :form="form" field="approval_criteria" />
        </label>

        <!-- Save Button -->
        <div class="text-right">
          <a-button
            :loading="form.busy"
            type="white"
            class="btn-white"
          >
            Save
          </a-button>
        </div>
      </form>
      <div v-else>
        This asset is not regulated.
      </div>
    </a-well>
  </a-well>
</template>
<script>
import Form from 'vform'
export default {
  props: {
    asset: { type: Object, required: true }
  },
  data: () => ({
    form: new Form({
      approval_server: null,
      approval_criteria: null
    })
  }),
  created () {
    this.form.approval_server = this.asset.approval_server
    this.form.approval_criteria = this.asset.approval_criteria
  },
  methods: {
    async update () {
      try {
        const { data } = await this.form.patch('/api/assets/' + this.$route.params.uuid + '/approval')
        this.$store.commit('asset/SET_ASSET', { asset: data.data })
        this.modal = false
      } catch (e) {
        throw e
      }
    }
  }
}
</script>
