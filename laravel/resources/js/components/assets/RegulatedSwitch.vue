<template>
  <form class="spaced-y-4 text-light" @submit.prevent="update" @keydown="form.onKeydown($event)">
    <div class="mt-4">
      <label class="inline-flex items-center">
        <input
          v-model="form.regulated"
          :value="true"
          type="radio"
          class="form-radio"
          name="regulated"
        >
        <span class="ml-2">Regulated</span>
      </label>
      <label class="inline-flex items-center ml-6">
        <input
          v-model="form.regulated"
          :value="false"
          type="radio"
          class="form-radio"
          name="regulated"
        >
        <span class="ml-2">Not Regulated</span>
      </label>
    </div>
  </form>
</template>
<script>
import Form from 'vform'
export default {
  props: {
    asset: { type: Object, required: true }
  },
  data: () => ({
    form: new Form({
      regulated: null
    })
  }),
  computed: {
    regulated () {
      return this.form.regulated
    }
  },
  watch: {
    regulated: function (newVal, oldVal) {
      if (this.form.originalData.regulated !== newVal) {
        this.save()
      }
    }
  },
  created () {
    let data = {}
    data.regulated = this.asset.regulated
    this.form = new Form(data)
  },
  methods: {
    async save () {
      const { data } = await this.form.patch('/api/assets/' + this.asset.uuid + '/regulated')
      this.$store.commit('asset/SET_ASSET', { asset: data.data })
    }
  }
}
</script>
