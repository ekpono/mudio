<template>
    <div>
        <h1>{{ greeting }}</h1>
    </div>
</template>

<script>
export default {
    data() {
        return {
            greeting: "",
        };
    },

    mounted() {
        const now = new Date();
        const hour = now.getHours();

        if (hour >= 5 && hour < 12) {
            this.greeting = "Good morning 👋";
        } else if (hour >= 12 && hour < 18) {
            this.greeting = "Good afternoon 🥳";
        } else {
            this.greeting = "Good evening 😴";
        }
    },
};
</script>
