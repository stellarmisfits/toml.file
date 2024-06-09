<template>
  <div class="py-8 mx-auto max-w-4xl">
    <div class="flex items-baseline justify-between">
      <div>
        <h2 class="text-light-heading">
          Toml Preview
          <a-pill class="ml-2" :color="(organization.published) ? 'green' : 'red'">
            <span>{{ (organization.published) ? 'Published' : 'Not Published' }}</span>
          </a-pill>
        </h2>
        <div v-if="organization.published" class="mt-1 text-light-secondary">
          <a target="_blank" :href="'https://' + organization.hosted_url + '/.well-known/stellar.toml'">{{ organization.hosted_url }}/.well-known/stellar.toml</a>
        </div>
      </div>
      <div class="flex-shrink-0 ml-4">
        <a class="inline-flex items-center spaced-x-2 btn btn-sm btn-white transition-all" :href="download" :download="organization.alias + '.toml'">
          Download
        </a>
      </div>
    </div>
    <div>
      <a-well class="mt-4">
        <div>
          <div class="bg-white px-6 py-4 overflow-auto">
            <div class="whitespace-pre-wrap font-mono text-xs text-gray-700">
{{ toml }} <!-- eslint-disable-line -->
            </div>
          </div>
        </div>
      </a-well>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  middleware: 'auth',

  components: {
    //
  },

  props: {
    organization: { type: Object, required: true }
  },

  computed: {
    ...mapState('org', ['toml']),
    download () {
      const blob = new Blob([this.toml], { type: 'text/plain' })
      return window.URL.createObjectURL(blob)
    }
  },

  created () {
    this.$store.dispatch('org/fetchToml', this.organization)
  },

  metaInfo () {
    return { title: 'TOML Preview' }
  },

  methods: {
    //
  }
}
</script>
