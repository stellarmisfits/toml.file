<template>
  <a-list-item
    :image-url="'https://identicon-api.herokuapp.com/' + account.public_key + '/250?format=png'"
    :image-title="account.alias"
    :action="action"
    :router-link="routerLink"
  >
    <div slot="body">
      <div class="flex items-center spaced-x-1">
        <h4 class="font-semibold text-lg leading-tight truncate">
          {{ account.name }}
          <a-pill class="ml-2" :color="(account.verified) ? 'green' : 'red'">
            {{ (account.verified) ? 'Verified' : 'Not Verified' }}
          </a-pill>
        </h4>
      </div>
      <div class="mt-1">
        <span>{{ account.alias }}*astrify.com</span>
      </div>
      <div class="mt-1">
        <span>{{ account.public_key }}</span>
      </div>
    </div>
    <div slot="action">
      <unlink
        v-if="action==='unlink'"
        resource-type="account"
        :resource-uuid="account.uuid"
        :resource-owner-uuid="resourceOwnerUuid"
        :resource-owner-type="resourceOwnerType"
      />
      <account-edit
        v-if="action==='edit'"
        :account="account"
        action="update"
      />
      <router-link
        v-if="action==='navigate'"
        :to="{ name: 'account.details', params: { uuid: account.uuid }}"
      >
        <fa icon="chevron-circle-right" class="hover:text-gray-400 cursor-pointer" />
      </router-link>
    </div>
    <div v-if="action==='edit'" slot="details">
      <div>
        <account-verify :organization="organization" :account="account" />
      </div>
    </div>
  </a-list-item>
</template>
<script>
import Unlink from '~/components/orgs/ResourceUnlink'
import AccountEdit from '~/components/accounts/Upsert'
import AccountVerify from '~/components/accounts/Verify'
import { mapGetters } from 'vuex'
export default {
  components: {
    Unlink,
    AccountEdit,
    AccountVerify
  },

  props: {
    account: { type: Object, required: true },
    action: {
      type: String,
      required: true,
      validator: (val) => ['edit', 'unlink', 'navigate'].includes(val)
    },
    resourceOwnerUuid: { type: String, default: null },
    resourceOwnerType: { type: String, default: null }
  },

  data: () => ({
    //
  }),

  computed: {
    ...mapGetters({
      organization: 'account/organization'
    }),
    routerLink: function () {
      return (this.action !== 'edit') ? { name: 'account.details', params: { uuid: this.account.uuid } } : null
    }
  }
}
</script>
