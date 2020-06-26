{#if location.pathname == '/' }
    <Main></Main>
{:else}
    <svelte:component this="{routes[controller.toLowerCase()]}"/>
{/if}

<script>
    window.axios = require('axios');

    import Main from './Main/Index.svelte';
    import User from './User/Index.svelte';
    import Post from './Post/Index.svelte';

    const routes = {
        main : Main,
        user : User,
        post : Post,
    }

    let controller = location.pathname.split("/")[1];

</script>


