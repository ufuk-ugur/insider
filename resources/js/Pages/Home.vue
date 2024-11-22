<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({teams: Object})
</script>

<template>
  <div class="w-96 mx-auto flex flex-col items-start gap-6">
    <h1 class="text-3xl font-extralight mx-auto">Tournament Teams</h1>


    <table class="w-full text-left">
      <thead class="text-white bg-gray-800">
      <tr>
        <th scope="col" class="p-4">
          Club
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="team in teams" class="bg-white border-b">
        <th scope="row" class="p-4 font-normal">
          {{ team.name }}
        </th>
      </tr>
      </tbody>
    </table>

    <Link
        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
        href="/fixture">
      Generate Fixtures
    </Link>
  </div>
</template>

<style scoped>

</style>
