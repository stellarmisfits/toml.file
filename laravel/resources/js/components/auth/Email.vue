<template>
  <form @submit.prevent="send" @keydown="form.onKeydown($event)">
    <alert-success :form="form" :message="status" />

    <!-- Email -->
    <div class="mt-4">
      <label class="uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
        {{ $t('email') }}
      </label>
      <input id="email" v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-input mt-2 mb-4 w-full" type="email" name="email">
      <has-error :form="form" field="email" />
    </div>

    <!-- Submit Button -->
    <div class="mt-2">
      <a-button class="mt-4" :loading="form.busy">
        {{ $t('send_password_reset_link') }}
      </a-button>
    </div>
  </form>
</template>

<script>
import Form from 'vform'

export default {
  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')
      this.status = data.status
      this.form.reset()
    }
  }
}
</script>
