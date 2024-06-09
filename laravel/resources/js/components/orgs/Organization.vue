<template>
  <a-list-item
    :image-url="org.logo"
    :image-title="org.name"
    :action="action"
    :router-link="routerLink"
  >
    <dropdown v-if="action==='edit'" slot="imageAction">
      <div slot="link" class="fill-current h-6 w-6 z-10 absolute right-0 mr-4 mt-2 text-white">
        <fa class="bg-gray-500 rounded-full" icon="chevron-circle-down" />
      </div>
      <template v-slot:dropdown="dropdownProps">
        <div class="py-2 w-32">
          <image-add model-type="organization" :model-uuid="org.uuid" @close="dropdownProps.closeDropdown" />
          <image-remove v-if="org.logo" model-type="organization" :model-uuid="org.uuid" @close="dropdownProps.closeDropdown" />
        </div>
      </template>
    </dropdown>
    <div slot="body">
      <div class="flex items-center spaced-x-1">
        <h4 class="font-semibold text-lg leading-tight truncate text-dark-heading">
          {{ org.name }} <span v-if="org.dba" class="font-normal text-base">
            ({{ org.dba }})
          </span>
        </h4>
      </div>
      <div class="text-sm">
        {{ org.alias }}
      </div>
      <div class="mt-1">
        {{ org.description }}
      </div>
    </div>
    <div slot="action">
      <unlink
        v-if="action==='unlink'"
        :resource-type="resourceOwnerType"
        :resource-uuid="resourceOwnerUuid"
        :org="org"
        @organizationUnlinked="$emit('organizationUnlinked', $event)"
      />
      <div v-if="action==='edit'" class="flex items-center">
        <a-pill class="ml-2" :color="(org.published) ? 'green' : 'red'">
          <span>{{ (org.published) ? 'Published' : 'Not Published' }}</span>
        </a-pill>
        <edit-organization
          :organization="org"
          action="update"
        />
      </div>
      <router-link
        v-if="action==='navigate'"
        :to="{ name: 'org.details', params: { uuid: org.uuid }}"
      >
        <fa icon="chevron-circle-right" class="hover:text-gray-400 cursor-pointer" />
      </router-link>
    </div>
    <div v-if="action==='edit'" slot="details">
      <div>
        <span class="form-label">Official URL</span>
        <span class="form-label-subtext">
          Note that the organization's stellar.toml must be hosted on this domain.
        </span>
        <span class="block mt-2">
          {{ org.url }}
        </span>
      </div>
    </div>
  </a-list-item>
</template>
<script>
import EditOrganization from '~/components/orgs/Upsert'
import Unlink from '~/components/orgs/OrganizationUnlink'
import ImageAdd from '~/components/ImageAdd'
import ImageRemove from '~/components/ImageRemove'
export default {
  components: {
    Unlink,
    EditOrganization,
    ImageAdd,
    ImageRemove
  },
  props: {
    org: { type: Object, required: true },
    action: {
      type: String,
      required: true,
      validator: (val) => ['edit', 'unlink', 'navigate'].includes(val)
    },
    resourceOwnerUuid: { type: String, default: '' },
    resourceOwnerType: { type: String, default: '' }
  },
  data: () => ({
    //
  }),
  computed: {
    routerLink: function () {
      return (this.action !== 'edit') ? { name: 'org.details', params: { uuid: this.org.uuid } } : null
    }
  }
}
</script>
