<section>
    {#if location.pathname.startsWith('/post/index') }

    {:else if location.pathname.startsWith('/post/create') }
        <Create csrf="{csrf}" theme="{theme}"></Create>
    {:else if location.pathname.startsWith('/post/edit') }
        <Create csrf="{csrf}" theme="{theme}" mode="edit"></Create>
    {:else if location.pathname.startsWith('/post/read') }
        <Read theme="{theme}"></Read>
    {/if}
</section>

<script>
    import Create from "./Create.svelte";
    import Read from "./Read.svelte";

    export let csrf;
    export let theme;
</script>

<style lang="scss" type="text/scss">

</style>