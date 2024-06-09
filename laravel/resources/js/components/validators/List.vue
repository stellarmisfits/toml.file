<template>
  <div v-if="validators" class="spaced-y-6">
    <list-item
      v-for="validator in validators"
      :key="validator.uuid"
      :validator="validator"
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
import ListItem from '~/components/validators/Validator'
export default {
  components: {
    ListItem
  },
  props: {
    validators: { type: Array, default: () => [] },
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
