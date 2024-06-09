<template>
  <div id="app2">
    <div class="min-h-screen flex bg-gray-900">
      <Sidebar />
      <div class="flex-grow flex flex-col">
        <Header />
        <div class="flex-grow flex flex-col">
          <child />
        </div>
      </div>
    </div>
    <portal-target name="modal" />
  </div>
</template>

<script>
import Sidebar from '~/components/nav/Sidebar'
import Header from '~/components/nav/Header'
// import Footer from '~/components/Footer'

export default {
  name: 'DashboardLayout',

  components: {
    Sidebar,
    Header
    // Footer
  }
}
</script>
