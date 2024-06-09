<template>
  <div>
    <button
      class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all"
    >
      <a @click.prevent="updateGlobalModal=!updateGlobalModal">Update Global Settings</a>
    </button>
    <Modal :open="updateGlobalModal">
      <div slot="title">
        Update Global Account Settings
      </div>
      <div slot="subtitle">
        Subtitle
      </div>
      <div slot="body">
        Body
      </div>
      <div slot="actions">
        <button class="btn btn-white transition-all" @click="updateGlobalModal=false">
          Cancel
        </button>
        <button type="button" class="relative ml-4 btn btn-black transition-all">
          Save
        </button>
      </div>
    </Modal>
  </div>
</template>
<script>
export default {
  data: () => ({
    updateGlobalModal: false
  })
}
</script>
