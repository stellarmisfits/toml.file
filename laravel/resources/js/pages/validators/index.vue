<template>
  <div class="flex-grow">
    <a-breadcrumbs>
      <span slot="title">Validators</span>
    </a-breadcrumbs>
    <div class="py-8 mx-auto max-w-4xl">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-lg text-light-heading">
            Validators
          </h2>
          <div class="mt-1 text-sm text-light-secondary">
            Validator records can be added to an organization's stellar.toml
            <a class="font-medium underline" target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0001.md#validator-information">Validators</a>
            section. These are optional records that work with the
            steps outlined in <a class="font-medium underline" target="_blank" href="https://github.com/stellar/stellar-protocol/blob/master/ecosystem/sep-0020.md">SEP-20</a>
            allowing you to declare any node(s) or public archives you maintain.
            You should add one validator record for each node your organization runs.
          </div>
        </div>
        <div class="flex-shrink-0 ml-4">
          <ValidatorCreate
            action="create"
          />
        </div>
      </div>
      <div class="mt-4">
        <ValidatorList
          :validators="validators"
          empty-message="No validators have been added to your current team."
          action="navigate"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ValidatorList from '~/components/validators/List'
import ValidatorCreate from '~/components/validators/Upsert'
export default {
  middleware: 'auth',

  components: {
    ValidatorList,
    ValidatorCreate
  },
  data () {
    return {
      //
    }
  },
  computed: {
    ...mapGetters('validator', ['validators'])
  },
  created () {
    this.$store.dispatch('validator/fetchValidators')
  }
}
</script>
