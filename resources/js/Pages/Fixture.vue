<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({weeks: Object})
</script>

<template>
    <div class="w-full flex flex-col items-start gap-6">
        <h1 class="text-3xl font-extralight mx-auto">Generated Fixtures</h1>

        <div class="w-full grid grid-cols-1 lg:grid-cols-4 gap-6">
            <table class="w-full text-left" v-for="(games, index) in weeks">
                <thead class="text-white bg-gray-800">
                <tr>
                    <th scope="col" colspan="3" class="p-4">
                        Week {{ index }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="game in games" class="bg-white border-b text-sm">
                    <th scope="row" class="p-4 font-normal w-1/2 text-right">{{ game.home_team.name }}</th>
                    <th scope="row" class="font-normal w-0">-</th>
                    <th scope="row" class="p-4 font-normal w-1/2">{{ game.away_team.name }}</th>
                </tr>
                </tbody>
            </table>
        </div>
        <Link
            class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
            href="/simulation"
            method="post"
            :data="{ week: 1 }"
        >
            Start Simulation
        </Link>
    </div>
</template>

<style scoped>

</style>
