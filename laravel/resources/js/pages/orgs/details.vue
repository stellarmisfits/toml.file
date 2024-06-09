<template>
  <div class="flex-grow">
    <div class="py-8 mx-auto">
      <organization
        action="edit"
        :org="organization"
      />
    </div>

    <div>
      <published
        :organization="organization"
      />
    </div>
  </div>
</template>

<script>
import Published from '~/components/orgs/Published'
import Organization from '~/components/orgs/Organization'
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.organization.name }
  },

  components: {
    Organization,
    Published
  },

  props: {
    organization: { type: Object, required: true }
  },

  methods: {
    //
  }
}
</script>
