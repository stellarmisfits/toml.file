<template>
  <a-well>
    <router-link
      :to="{ name: 'validator.details', params: { uuid: validator.uuid }}"
      class="flex justify-between items-center px-6 py-6 hover:bg-gray-100 cursor-pointer"
    >
      <div class="flex-grow">
        <div class="flex justify-between items-baseline">
          <span>{{ validator.name }}</span>
        </div>
        <div class="flex justify-between items-baseline mt-1">
          <span
            class="text-sm text-gray-600"
          >
            <span
              class="inline-flex items-center"
            >
              <span>{{ validator.account_public_key }}</span>
            </span>
          </span>
        </div>
      </div>
      <unlink
        v-if="action==='unlink'"
        :resource-uuid="validator.uuid"
        resource-type="validator"
      >
        <div slot="title">
          Are you sure you want to remove the validator from the organization?
        </div>
      </unlink>
      <fa icon="chevron-circle-right" class="hover:text-gray-400 cursor-pointer" />
    </router-link>
  </a-well>
</template>
<script>
import Unlink from '~/components/orgs/ResourceUnlink'
export default {
  components: {
    Unlink
  },
  props: {
    validator: { type: Object, required: true },
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
