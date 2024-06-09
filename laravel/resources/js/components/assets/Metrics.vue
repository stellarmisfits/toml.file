<template>
  <a-well class="px-6 py-4">
    <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
      <!-- display_decimals -->
      <label class="block">
        <span class="form-label">Display Decimals</span>
        <input v-model="form.display_decimals" type="number" class="form-input" :class="{ 'is-invalid': form.errors.has('display_decimals') }">
        <has-error :form="form" field="display_decimals" />
      </label>

      <!-- fixed_number -->
      <label class="block">
        <span class="form-label">Fixed Number</span>
        <input v-model="form.fixed_number" type="number" class="form-input" :class="{ 'is-invalid': form.errors.has('fixed_number') }">
        <has-error :form="form" field="fixed_number" />
      </label>

      <!-- max_number -->
      <label class="block">
        <span class="form-label">Max Number</span>
        <input v-model="form.max_number" type="number" class="form-input" :class="{ 'is-invalid': form.errors.has('max_number') }">
        <has-error :form="form" field="max_number" />
      </label>

      <!-- is_unlimited -->
      <label class="block">
        <label class="inline-flex items-center">
          <input
            v-model="form.is_unlimited"
            :value="true"
            type="radio"
            class="form-radio"
            name="anchored"
          >
          <span class="ml-2">Unlimited</span>
        </label>
        <label class="inline-flex items-center ml-6">
          <input
            v-model="form.is_unlimited"
            :value="false"
            type="radio"
            class="form-radio"
            name="anchored"
          >
          <span class="ml-2">Not Unlimited</span>
        </label>
        <has-error :form="form" field="is_unlimited" />
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
      display_decimals: null,
      fixed_number: null,
      max_number: null,
      is_unlimited: null
    })
  }),
  created () {
    this.form.display_decimals = this.asset.display_decimals
    this.form.fixed_number = this.asset.fixed_number
    this.form.max_number = this.asset.max_number
    this.form.is_unlimited = this.asset.is_unlimited
  },
  methods: {
    async update () {
      try {
        const { data } = await this.form.patch('/api/assets/' + this.$route.params.uuid + '/metrics')
        this.$store.commit('asset/SET_ASSET', { asset: data.data })
      } catch (e) {
        throw e
      }
    }
  }
}
</script>
