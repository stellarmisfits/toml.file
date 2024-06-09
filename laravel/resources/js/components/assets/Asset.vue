<template>
  <a-list-item
    :image-url="asset.logo"
    :image-title="asset.name"
    :action="action"
    :router-link="routerLink"
  >
    <dropdown v-if="action==='edit'" slot="imageAction">
      <div slot="link" class="fill-current h-6 w-6 z-10 absolute right-0 mr-4 mt-2 text-white">
        <fa class="bg-gray-500 rounded-full" icon="chevron-circle-down" />
      </div>
      <template v-slot:dropdown="dropdownProps">
        <div class="py-2 w-32">
          <image-add model-type="asset" :model-uuid="asset.uuid" @close="dropdownProps.closeDropdown" />
          <image-remove v-if="asset.logo" model-type="asset" :model-uuid="asset.uuid" @close="dropdownProps.closeDropdown" />
        </div>
      </template>
    </dropdown>
    <div slot="body">
      <div class="flex items-center spaced-x-1">
        <h4 class="font-semibold text-lg leading-tight truncate">
          {{ asset.name }}
        </h4>
        <a-pill class="ml-2" color="blue">
          {{ asset.status }}
        </a-pill>
      </div>
      <div class="mt-1">
        {{ asset.code }}
      </div>
      <div class="mt-2">
        {{ asset.description }}
      </div>
    </div>
    <div slot="action">
      <unlink v-if="action==='unlink'" resource-type="asset" :resource-uuid="asset.uuid" />
      <update-asset
        v-if="action==='edit'"
        :asset="asset"
        action="update"
      />
      <router-link
        v-if="action==='navigate'"
        :to="{ name: 'asset.details', params: { uuid: asset.uuid }}"
      >
        <fa icon="chevron-circle-right" class="hover:text-gray-400 cursor-pointer" />
      </router-link>
    </div>
    <div v-if="action==='edit'" slot="details">
      <div class="mt-2">
        {{ asset.code_template }}
      </div>
      <div class="mt-2">
        {{ asset.conditions }}
      </div>
    </div>
  </a-list-item>
</template>
<script>
import Unlink from '~/components/orgs/ResourceUnlink'
import ImageAdd from '~/components/ImageAdd'
import ImageRemove from '~/components/ImageRemove'
import UpdateAsset from '~/components/assets/Upsert'
export default {
  components: {
    Unlink,
    ImageAdd,
    ImageRemove,
    UpdateAsset
  },
  props: {
    asset: { type: Object, required: true },
    action: {
      type: String,
      required: true,
      validator: (val) => ['edit', 'unlink', 'navigate'].includes(val)
    }
  },
  data: () => ({
    //
  }),
  computed: {
    routerLink: function () {
      return (this.action !== 'edit') ? { name: 'asset.details', params: { uuid: this.asset.uuid } } : null
    }
  }
}
</script>
