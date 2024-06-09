<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-200">
    <router-link :to="{ name: 'welcome' }">
      <a-logo class="h-24 w-24 text-blue-800 hover:text-gray-600" />
    </router-link>
    <a-well class="my-6 p-6 w-96">
      <div class="text-gray-700 text-sm spaced-y-4">
        <h2 class="text-lg">
          Privacy and Terms Agreement
        </h2>
        <div>
          You must agree to the latest
          <a href="/terms" target="_blank" class="underline cursor-pointer">Terms of Service</a> and <a href="/privacy" target="_blank" class="underline cursor-pointer">Privacy Policy</a>
          before continuing.
        </div>

        <alert-error :form="form" />
        <div class="flex justify-end">
          <a-button class="mt-4" @click="save">
            I Agree
          </a-button>
        </div>
      </div>
    </a-well>
  </div>
</template>

<script>
import Form from 'vform'
export default {
  layout: 'auth',

  metaInfo () {
    return { title: 'Terms of Use' }
  },

  data: () => ({
    openTerms: false,
    openPrivacy: false,
    form: new Form({
      accepted: false
    })
  }),

  methods: {
    async save () {
      this.form.accepted = true
      const { data } = await this.form.post('/api/user-agreements')
      this.form.reset()
      this.$store.commit('auth/FETCH_USER_SUCCESS', { user: data.data })

      this.$router.push({ name: 'dashboard' })
    }
  }
}
</script>
