<template>
  <form class="spaced-y-4 text-light" @submit.prevent="update" @keydown="form.onKeydown($event)">
    <div class="mt-4">
      <label class="inline-flex items-center">
        <input
          v-model="form.anchored"
          :value="true"
          type="radio"
          class="form-radio"
          name="anchored"
        >
        <span class="ml-2">Anchored</span>
      </label>
      <label class="inline-flex items-center ml-6">
        <input
          v-model="form.anchored"
          :value="false"
          type="radio"
          class="form-radio"
          name="anchored"
        >
        <span class="ml-2">Not Anchored</span>
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
      anchored: null
    })
  }),
  computed: {
    anchored () {
      return this.form.anchored
    }
  },
  watch: {
    anchored: function (newVal, oldVal) {
      if (this.form.originalData.anchored !== newVal) {
        this.save()
      }
    }
  },
  created () {
    let data = {}
    data.anchored = this.asset.anchored
    this.form = new Form(data)
  },
  methods: {
    async save () {
      const { data } = await this.form.patch('/api/assets/' + this.asset.uuid + '/anchored')
      this.$store.commit('asset/SET_ASSET', { asset: data.data })
    }
  }
}
</script>
