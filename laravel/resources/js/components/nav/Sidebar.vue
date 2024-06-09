<template>
  <div class="flex-shrink-0 w-64 bg-white">
    <router-link :to="{ name: 'dashboard' }" class="hover:text-gray-400">
      <div
        class="flex items-center h-16 px-4 text-3xl text-dark font-light"
      >
        <a-logo
          class="fill-current h-10 w-10"
        />
        <div class="ml-2" style="padding-top: 2px;">
          astrify
        </div>
      </div>
    </router-link>
    <div v-if="authenticated">
      <div class="px-6 py-6 border-t border-gray-400">
        <router-link class="text-sm text-dark-heading uppercase font-bold tracking-widest" :to="{ name: 'dashboard' }" active-class="underline" exact>
          Organizations
        </router-link>
        <ul class="mt-3">
          <li v-for="org in orgs" :key="org.uuid" class="mt-3 text-dark">
            <router-link
              :to="{ name: 'org.details', params: { uuid: org.uuid }}"
              active-class="underline"
            >
              {{ org.name }}
            </router-link>
          </li>
        </ul>
      </div>
      <div class="px-6 py-6 border-t border-gray-400">
        <h4
          class="text-sm text-dark-heading uppercase font-bold tracking-widest"
        >
          Resources
        </h4>
        <ul class="mt-3 text-dark">
          <li class="mt-3">
            <router-link :to="{ name: 'accounts'}" active-class="underline">
              Accounts
            </router-link>
          </li>
          <li class="mt-3">
            <router-link :to="{ name: 'assets'}" active-class="underline">
              Assets
            </router-link>
          </li>
          <li class="mt-3">
            <router-link :to="{ name: 'principals'}" active-class="underline">
              Principals
            </router-link>
          </li>
          <li class="mt-3">
            <router-link :to="{ name: 'validators'}" active-class="underline">
              Validators
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
    //
  }),
  computed: mapGetters({
    orgs: 'org/orgs',
    accounts: 'account/accounts',
    authenticated: 'auth/check'
  })
}
</script>
