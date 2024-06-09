<template>
  <div class="flex flex-col justify-center items-center bg-blue-900">
    <a-well class="text-center p-6">
      <h3 class="mb-4 text-2xl">
        404 {{ $t('page_not_found') }}
      </h3>

      <div class="py-4">
        <router-link :to="{ name: 'dashboard' }">
          <a-button>{{ $t('go_home') }}</a-button>
        </router-link>
      </div>
    </a-well>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  layout: 'landing'
}
</script>
