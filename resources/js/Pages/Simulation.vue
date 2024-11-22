<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    board: Object,
    games: Object,
    week: Number,
    maxWeek: Number,
})
</script>

<template>
    <div class="w-full flex flex-col items-start gap-6">
        <h1 class="text-3xl font-extralight text-center">Simulation</h1>

        <div class="w-full flex flex-col lg:flex-row items-start gap-6">
            <table class="w-full lg:max-w-96 text-left">
                <thead class="text-white bg-gray-800">
                <tr>
                    <th scope="col" colspan="3" class="p-4">
                        Week {{ week }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="game in games" class="bg-white border-b">
                    <th scope="row" class="p-4 font-normal w-1/2 text-right">{{ game.home_team.name }}
                        {{ game.home_score }}
                    </th>
                    <th scope="row" class="font-normal w-0">-</th>
                    <th scope="row" class="p-4 font-normal w-1/2">{{ game.away_score }} {{ game.away_team.name }}</th>
                </tr>
                </tbody>
            </table>
            <table class="w-full text-left col-3">
                <thead class="text-white bg-gray-800">
                <tr>
                    <th scope="col" class="p-4">
                        Club
                    </th>
                    <th scope="col" class="p-4">
                        Won
                    </th>
                    <th scope="col" class="p-4">
                        Drawn
                    </th>
                    <th scope="col" class="p-4">
                        Lost
                    </th>
                    <th scope="col" class="p-4">
                        GF
                    </th>
                    <th scope="col" class="p-4">
                        GA
                    </th>
                    <th scope="col" class="p-4">
                        GD
                    </th>
                    <th scope="col" class="p-4">
                        Points
                    </th>
                    <th scope="col" class="p-4">
                        Predictions
                        Rank
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="team in board" class="bg-white border-b">
                    <th scope="row" class="p-4 font-normal">
                        {{ team.name }}
                    </th>
                    <th scope="row" class="p-4 font-normal">
                        {{ team.won }}
                    </th>
                    <th scope="row" class="p-4 font-normal">
                        {{ team.drawn }}
                    </th>
                    <th scope="row" class="p-4 font-normal">
                        {{ team.lost }}
                    </th>
                    <th scope="row" class="p-4 font-normal">
                        {{ team.gf }}
                    </th>
                    <th scope="row" class="p-4 font-normal">
                        {{ team.ga }}
                    </th>
                    <th scope="row" class="p-4 font-normal">
                        {{ team.gd }}
                    </th>
                    <th scope="row" class="p-4 font-bold">
                        {{ team.points }}
                    </th>
                    <th scope="row" class="p-4 font-normal">
                        {{ team.predicted_rank }}
                    </th>
                </tr>
                </tbody>
            </table>
        </div>
        <div>
            <Link
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                href="/simulation"
                method="post"
            >
                Play All Weeks
            </Link>
            <Link
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                href="/simulation"
                method="post"
                :data="{ week: week + 1 }"
                v-if="maxWeek > week"
            >
                Play Next Week
            </Link>
            <Link
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                href="/"
            >
                Reset Data
            </Link>
        </div>
    </div>
</template>

<style scoped>

</style>
