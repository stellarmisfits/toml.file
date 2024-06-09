<template>
  <div v-if="principals && principals.length" class="spaced-y-6">
    <principal
      v-for="principal in principals"
      :key="principal.uuid"
      :principal="principal"
      :action="action"
    />
  </div>
  <a-well v-else class="px-6 py-12">
    <a-empty-list
      :message="emptyMessage"
    />
  </a-well>
</template>
<script>
import Principal from '~/components/principals/Principal'
export default {
  components: {
    Principal
  },
  props: {
    principals: { type: Array, default: () => [] },
    emptyMessage: { type: String, required: true },
    action: {
      type: String,
      required: true,
      validator: (val) => ['edit', 'unlink', 'navigate'].includes(val)
    }
  },
  data: () => ({
    //
  })
}
</script>
