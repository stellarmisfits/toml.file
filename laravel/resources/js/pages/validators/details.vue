<template>
  <div>
    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading">
            Validator Details
          </h2>
        </div>
        <div class="flex-shrink-0 ml-4">
          <ValidatorCreate
            :validator="validator"
            action="update"
          />
        </div>
      </div>
      <div>
        <a-well class="mt-4">
          <div class="px-6 py-4 bg-blue-800">
            <div class="text-light-heading">
              {{ validator.name }}
            </div>
            <div class="mt-2 text-light-secondary">
              {{ validator.account_public_key }}
            </div>
          </div>
          <div>
            <div>
              <div class="flex justify-between items-center px-6 py-4 border-t">
                <div class="text-sm text-gray-900">
                  {{ validator.alias }}
                </div>
                <div class="text-sm text-gray-700">
                  Alias
                </div>
              </div>
              <div class="flex justify-between items-center px-6 py-4 border-t">
                <div class="text-sm text-gray-900">
                  {{ validator.host }}
                </div>
                <div class="text-sm text-gray-700">
                  Host
                </div>
              </div>
              <div class="flex justify-between items-center px-6 py-4 border-t">
                <div class="text-sm text-gray-900">
                  {{ validator.history }}
                </div>
                <div class="text-sm text-gray-700">
                  History
                </div>
              </div>
            </div>
          </div>
        </a-well>
      </div>
    </div>
    <div class="py-8 mx-auto">
      <div class="flex items-baseline justify-between">
        <div>
          <h2 class="text-light-heading">
            Organizations
          </h2>
          <div class="mt-1 text-light-secondary">
            This validator is linked to the following organizations.
          </div>
        </div>
        <div class="flex-shrink-0 ml-4">
          <link-organization
            resource-type="validator"
            :resource-uuid="validator.uuid"
            :unlinked-orgs="unlinkedOrgs"
            @organizationLinked="updateOrgs"
          />
        </div>
      </div>
      <div class="mt-4">
        <OrganizationList
          action="unlink"
          :orgs="linkedOrgs"
          empty-message="No organizations found."
          resource-owner-type="validator"
          :resource-owner-uuid="validator.uuid"
          @organizationUnlinked="updateOrgs"
        />
      </div>
    </div>
  </div>
</template>

<script>
import ValidatorCreate from '~/components/validators/Upsert'
import OrganizationList from '~/components/orgs/List'
import LinkOrganization from '~/components/orgs/OrganizationLink'
import { mapGetters } from 'vuex'
export default {
  middleware: 'auth',

  components: {
    OrganizationList,
    ValidatorCreate,
    LinkOrganization
  },

  props: {
    validator: { type: Object, required: true }
  },

  metaInfo () {
    return { title: 'Validator Details' }
  },

  data: () => ({
    //
  }),

  computed: {
    ...mapGetters({
      linkedOrgs: 'validator/linkedOrgs',
      unlinkedOrgs: 'validator/unlinkedOrgs'
    })
  },

  created () {
    this.updateOrgs()
  },

  methods: {
    updateOrgs () {
      this.$store.dispatch('validator/fetchLinkedOrgs', { resourceUuid: this.validator.uuid, resourceType: 'validators' })
      this.$store.dispatch('validator/fetchUnlinkedOrgs', { resourceUuid: this.validator.uuid, resourceType: 'validators' })
    }
  }
}
</script>
