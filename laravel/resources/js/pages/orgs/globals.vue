<template>
  <div class="flex-grow">
    <!-- documentation-->
    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading">
            Account Information
          </h2>
          <div class="mt-1 text-light-secondary">
            <div class="max-w-2xl">
              These are global fields in the stellar.toml file.
            </div>
          </div>
        </div>
        <div class="flex-shrink-0 ml-4">
          <a target="_blank" class="text-light-secondary hover:text-blue-500" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0001.md#account-information">
            <fa icon="external-link-alt" />
          </a>
        </div>
      </div>
      <hr class="my-10 border-b border-gray-800">
      <div>
        <globals
          :organization="organization"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Globals from '~/components/orgs/Globals'
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.organization.name }
  },

  components: {
    Globals
  },

  props: {
    organization: { type: Object, required: true }
  },

  methods: {
    //
  }
}
</script>
