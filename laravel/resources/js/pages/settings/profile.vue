<template>
  <div class="flex -mx-2">
    <div class="w-1/3 px-2">
      <h3 class="text-md font-semibold">
        Contact Information
      </h3>
      <div class="mt-2 text-sm text-gray-800">
        Update your account's name and email address.
      </div>
    </div>
    <div class="w-2/3 px-2">
      <div class="px-6 py-4 bg-white rounded-lg shadow-md overflow-hidden">
        <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
          <alert-success :form="form" :message="$t('info_updated')" />
          <label class="block"><span class="form-label">Name</span>
            <input
              v-model="form.name"
              :class="{ 'is-invalid': form.errors.has('name') }"
              type="text"
              name="name"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="name" />
          </label>
          <label class="block">
            <span class="form-label">Email</span>
            <input
              v-model="form.email"
              :class="{ 'is-invalid': form.errors.has('email') }"
              type="email"
              name="email"
              required="required"
              class="form-input mt-1"
            >
            <has-error :form="form" field="email" />
          </label>

          <!-- Submit Button -->
          <div class="text-right">
            <a-button
              :loading="form.busy"
              type="white"
              class="btn-white"
            >
              Save
            </a-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: ''
    })
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    if (!window.config.loginEnabled) {
      this.$router.push('/app')
    }

    // Fill the form with user data.
    let data = {}
    this.form.keys().forEach(key => {
      data[key] = this.user[key]
    })
    this.form = new Form(data)
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
