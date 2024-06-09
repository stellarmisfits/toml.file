<template>
  <portal to="modal">
    <div v-if="showModal" class="fixed z-100 inset-0 flex items-center justify-center">
      <transition
        enter-active-class="transition-all transition-duration-25 transition-ease-out"
        leave-active-class="transition-all transition-duration-25 transition-ease-in"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
        appear
        @before-leave="backdropLeaving = true"
        @after-leave="backdropLeaving = false"
      >
        <div v-if="showBackdrop">
          <div class="absolute inset-0 bg-black opacity-75" @click="close" />
        </div>
      </transition>

      <transition
        enter-active-class="transition-all transition-duration-100 transition-ease-out"
        leave-active-class="transition-all transition-duration-100 transition-ease-in"
        enter-class="opacity-0 scale-75"
        enter-to-class="opacity-100 scale-100"
        leave-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-75"
        appear
        @before-leave="cardLeaving = true"
        @after-leave="cardLeaving = false"
      >
        <div v-if="showContent" class="flex flex-col justify-center relative w-full mx-auto max-w-2xl h-screen py-6">
          <div class="flex flex-col bg-white rounded-lg shadow-2xl overflow-hidden max-h-full">
            <div class="flex-none px-6 py-4">
              <div class="text-lg">
                <slot name="title" />
                <div class="mt-1 text-sm text-gray-600">
                  <slot name="subtitle" />
                </div>
              </div>
            </div>
            <div class="px-6 py-4 overflow-auto">
              <div v-if="alert" class="mb-4">
                <strong class="text-red-900">Whoops! Something went wrong.</strong>
                <ul class="mt-3 list-disc list-inside text-red-800 text-sm">
                  <li>The variables field is required.</li>
                  <slot name="alert" />
                </ul>
              </div>
              <slot name="body" />
            </div>
            <div class="flex-none px-6 py-4 bg-gray-100 text-right">
              <slot name="actions" />
            </div>
          </div>
        </div>
      </transition>
    </div>
  </portal>
</template>
<script>
export default {
  name: 'Modal',
  props: {
    alert: { type: Boolean, default: false },
    open: { type: Boolean, default: false }
  },
  data () {
    return {
      showModal: false,
      showBackdrop: false,
      showContent: false,
      backdropLeaving: false,
      cardLeaving: false
    }
  },
  computed: {
    leaving () {
      return this.backdropLeaving || this.cardLeaving
    }
  },
  watch: {
    open: {
      handler: function (newValue) {
        if (newValue) {
          this.show()
        } else {
          this.close()
        }
      },
      immediate: true
    },
    leaving (newValue) {
      if (newValue === false) {
        this.showModal = false
        this.$emit('close')
      }
    }
  },
  created () {
    const onEscape = (e) => {
      if (this.open && e.keyCode === 27) {
        this.close()
      }
    }
    document.addEventListener('keydown', onEscape)
    this.$once('hook:destroyed', () => {
      document.removeEventListener('keydown', onEscape)
    })
  },
  methods: {
    show () {
      this.showModal = true
      this.showBackdrop = true
      this.showContent = true
    },
    close () {
      this.showBackdrop = false
      this.showContent = false
    }
  }
}
</script>
