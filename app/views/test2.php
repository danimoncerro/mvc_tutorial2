<script src="<?= BASE_URL ?>frontend/js/vue.js"></script>

<?php


$title = "Test 2";

?>

<div id="app">
    <h2> {{ titlu }} </h2>
</div>

<script>

    const { createApp, ref, onMounted } = Vue;

    const app = createApp({
        setup() {
            const titlu = ref();

            const afiseazaTitlu = () => {
                titlu.value = "Test 2 in actiune!";
            };

            onMounted(() => {
                afiseazaTitlu();
            });

            return {
                titlu,
            };

        }
    });

    app.mount('#app');

</script>

