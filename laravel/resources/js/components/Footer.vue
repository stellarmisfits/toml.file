<template>
  <div class="flex bg-gray-800 px-16 py-8">
    <div class="sm:w-1/4 h-auto">
      <div class="text-gray-400 font-medium mb-2">
        Orange
      </div>
      <ul class="list-reset leading-normal text-gray-400 text-sm">
        <li>
          One
        </li>
        <li>
          Two
        </li>
        <li>
          Three
        </li>
        <li>
          Four
        </li>
        <li>
          Five
        </li>
        <li>
          Six
        </li>
        <li>
          Seven
        </li>
        <li>
          Eight
        </li>
      </ul>
    </div>
    <div class="sm:w-1/4 h-auto sm:mt-0 mt-8">
      <div class="text-gray-400 font-medium mb-2">
        Blue
      </div>
      <ul class="list-reset leading-normal text-gray-400 text-sm">
        <li>
          One
        </li>
        <li>
          Two
        </li>
        <li>
          Three
        </li>
      </ul>

      <div class="text-gray-400 font-medium mb-2 mt-4">
        Blue-light
      </div>
      <ul class="list-reset leading-normal text-gray-400 text-sm">
        <li>
          One
        </li>
        <li>
          Two
        </li>
        <li>
          Three
        </li>
      </ul>
    </div>
    <div class="sm:w-1/2 sm:mt-0 mt-8 h-auto">
      <div class="text-gray-400 font-medium">
        Newsletter
      </div>
      <p class="text-gray-400 text-sm leading-normal">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi, consectetur.
      </p>
      <div class="mt-4 flex">
        <input type="text" class="form-input mr-4" placeholder="Your email address">
        <button class="btn bg-gray-500 text-gray-200">
          Subscribe
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { loadMessages } from '~/plugins/i18n'

export default {
  computed: mapGetters({
    locale: 'lang/locale',
    locales: 'lang/locales'
  }),

  methods: {
    setLocale (locale) {
      if (this.$i18n.locale !== locale) {
        loadMessages(locale)
        this.$store.dispatch('lang/setLocale', { locale })
      }
    }
  }
}
</script>
