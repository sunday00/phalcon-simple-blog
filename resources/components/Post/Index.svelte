<section>
    {#if location.pathname.startsWith('/post/index') }

    {:else if location.pathname.startsWith('/post/create') }
        <Create></Create>
    {/if}
</section>

<script>
    import Create from "./Create.svelte";

</script>

<style lang="scss" type="text/scss">

</style>