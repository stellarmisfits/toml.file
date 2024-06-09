<template>
  <a-list-item
    :image-url="principal.photo_url"
    :image-title="principal.name"
    :action="action"
  >
    <div slot="body">
      <div class="flex items-center spaced-x-1">
        <h4 class="font-semibold text-lg leading-tight truncate">
          {{ principal.name }}
        </h4>
      </div>
      <div class="mt-1">
        {{ principal.email }}
      </div>
    </div>
    <div slot="action">
      <unlink v-if="action==='unlink'" resource-type="principal" :resource-uuid="principal.uuid" />
      <edit
        v-if="action==='edit'"
        :principal="principal"
        action="update"
      />
      <router-link
        v-if="action==='navigate'"
        :to="{ name: 'principal.details', params: { uuid: principal.uuid }}"
      >
        <fa icon="chevron-circle-right" class="hover:text-gray-400 cursor-pointer" />
      </router-link>
    </div>
    <div v-if="action==='edit'" slot="details">
      <div v-if="principal.keybase" class="mt-2">
        {{ principal.keybase }}
      </div>
      <div v-if="principal.telegram" class="mt-2">
        {{ principal.telegram }}
      </div>
      <div v-if="principal.twitter" class="mt-2">
        {{ principal.twitter }}
      </div>
      <div v-if="principal.github" class="mt-2">
        {{ principal.github }}
      </div>
      <div v-if="principal.id_photo_hash" class="mt-2">
        {{ principal.id_photo_hash }}
      </div>
      <div v-if="principal.verification_photo_hash" class="mt-2">
        {{ principal.verification_photo_hash }}
      </div>
    </div>
  </a-list-item>
</template>
<script>
import Edit from '~/components/principals/Upsert'
import Unlink from '~/components/orgs/ResourceUnlink'
export default {
  components: {
    Unlink,
    Edit
  },
  props: {
    principal: { type: Object, required: true },
    action: { type: String, default: null }
  },
  data: () => ({
    //
  })
}
</script>
