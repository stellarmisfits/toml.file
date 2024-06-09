<template>
  <form @submit.prevent="login" @keydown="form.onKeydown($event)">
    <!-- Email -->
    <div class="w-full">
      <label class="uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
        {{ $t('email') }}
      </label>
      <input id="email" v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" name="email" class="form-input mt-2 mb-4 w-full" type="email" required autocomplete="email" placeholder="username@example.com">
      <has-error :form="form" field="email" />
    </div>

    <!-- Password -->
    <div class="w-full">
      <label class="uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="password">
        {{ $t('password') }}
      </label>
      <input id="password" v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" name="password" class="form-input mt-2 mb-4 w-full" type="password" placeholder="******************">
      <has-error :form="form" field="password" />
    </div>

    <!-- Remember Me -->
    <div>
      <div class="flex items-center justify-between uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
        <tw-checkbox v-model="remember" name="remember">
          {{ $t('remember_me') }}
        </tw-checkbox>
      </div>
    </div>

    <div class="mt-4 flex flex-col md:flex-row items-start md:items-center justify-between">
      <!-- Submit Button -->
      <a-button :loading="form.busy">
        {{ $t('login') }}
      </a-button>
      <div class="ml-0 md:ml-2 mb-2">
        <router-link :to="{ name: 'password.request' }" class="align-baseline uppercase tracking-wide text-gray-800 text-xs no-underline hover:text-gray-600">
          {{ $t('forgot_password') }}
        </router-link>
      </div>
    </div>
    <!-- GitHub Login Button -->
    <div class="border-t mt-4 pt-4">
      <login-with-github />
    </div>
  </form>
</template>
<script>
import Form from 'vform'
import LoginWithGithub from '~/components/LoginWithGithub'

export default {
  middleware: 'guest',

  components: {
    LoginWithGithub
  },

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'dashboard' })
    }
  }
}
</script>
