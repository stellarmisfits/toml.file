<template>
  <div>
    <div class="flex -mx-2">
      <div class="w-1/3 px-2">
        <h3 class="text-md font-semibold">
          Password
        </h3>
        <div class="mt-2 text-sm text-gray-800">
          Use a long, random password to keep your account secure.
        </div>
      </div>
      <div class="w-2/3 px-2">
        <div class="px-6 py-4 bg-white rounded-lg shadow-md overflow-hidden">
          <form class="spaced-y-4" @submit.prevent="update" @keydown="form.onKeydown($event)">
            <alert-success :form="form" :message="$t('password_updated')" />

            <!-- Current (old) Password -->
            <label class="block">
              <span class="form-label">Current Password</span>
              <input
                v-model="form.old_password"
                :class="{ 'is-invalid': form.errors.has('old_password') }"
                type="password"
                name="old_password"
                required="required"
                class="form-input mt-1"
              >
              <has-error :form="form" field="old_password" />
            </label>

            <!-- Password -->
            <label class="block">
              <span class="form-label">New Password</span>
              <input
                v-model="form.password"
                :class="{ 'is-invalid': form.errors.has('password') }"
                type="password"
                name="password"
                required="required"
                class="form-input mt-1"
              >
              <has-error :form="form" field="password" />
            </label>

            <!-- Password Confirmation -->
            <label class="block">
              <span class="form-label">Confirm Password</span>
              <input
                v-model="form.password_confirmation"
                :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
                type="password"
                name="password_confirmation"
                required="required"
                class="form-input mt-1"
              >
              <has-error :form="form" field="password_confirmation" />
            </label>

            <!-- Save Button -->
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

    <hr class="my-10 border-b">
    <div class="flex -mx-2">
      <div class="w-1/3 px-2">
        <h3 class="text-md font-semibold">
          Two Factor Authentication
        </h3>
        <div class="mt-2 text-sm text-gray-800">
          Add additional security to your account using two factor
          authentication.
        </div>
      </div>
      <div class="w-2/3 px-2">
        <div class="px-6 py-4 bg-white rounded-lg shadow-md overflow-hidden">
          <div class="flex">
            <div class="flex-1">
              <div>
                <strong class="font-semibold">Two factor authentication is
                  enabled.</strong>
              </div>
              <div class="mt-2 text-sm text-gray-600">
                When two factor authentication is enabled, you will be prompted
                for a secure, random token during authentication.
                You may retrieve this token from your phone's Google
                Authenticator application.
              </div>
              <div class="mt-6">
                <button type="submit" class="relative btn btn-white transition-all">
                  Disable
                </button>
              </div>
            </div> <!---->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      old_password: '',
      password: '',
      password_confirmation: ''
    })
  }),

  created () {
    if (!window.config.loginEnabled) {
      this.$router.push('/app')
    }
  },

  methods: {
    async update () {
      await this.form.patch('/api/settings/password')
      this.form.reset()
    }
  }
}
</script>
