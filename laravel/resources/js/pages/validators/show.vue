<template>
  <div v-if="validator" class="flex-grow">
    <a-breadcrumbs>
      <span slot="title">
        <router-link :to="{ name: 'validators' }">
          Validators
        </router-link>
        <span class="mx-3 text-gray-400 font-light text-2xl leading-none">/</span>{{ validator.name }}</span>
      <delete-resource
        slot="dropdown"
        :resource="validator"
        resource-type="VALIDATOR"
      />
    </a-breadcrumbs>
    <div class="px-12 py-8 mx-auto max-w-4xl">
      <transition name="fade" mode="out-in">
        <router-view :validator="validator" />
      </transition>
    </div>
  </div>
</template>

<script>
import ABreadcrumbs from '~/components/Breadcrumbs'
import DeleteResource from '~/components/DeleteResource'
import { mapGetters } from 'vuex'
export default {
  components: { ABreadcrumbs, DeleteResource },
  middleware: 'auth',
  data () {
    return {
      tabs: [
        {
          name: 'General',
          route: 'account.details',
          params: { uuid: this.$route.params.uuid }
        }
      ]
    }
  },
  computed: {
    ...mapGetters('validator', ['getValidatorByUuid']),
    validator: function () {
      return this.getValidatorByUuid(this.$route.params.uuid)
    }
  },
  async created () {
    try {
      await this.$store.dispatch('validator/fetchValidator', this.$route.params.uuid)
    } catch (e) {
      if (e.response.status === 404) {
        this.$router.push({ name: '404' })
      }
    }
  }
}
</script>
