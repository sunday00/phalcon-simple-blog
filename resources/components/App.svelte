{#if location.pathname === '/' || location.pathname === ''}
    <Main theme="{theme}"></Main>
{:else}
    <svelte:component this="{routes[controller.toLowerCase()]}" csrf="{csrf}" theme="{theme}" />
{/if}

<script>
    window.axios = require('axios');

    import Main from './Main/Index.svelte';
    import User from './User/Index.svelte';
    import Post from './Post/Index.svelte';
    import Admin from './Admin/Index.svelte';
    import About from './About/Index.svelte';

    export let csrf;
    export let theme;

    const routes = {
        main : Main,
        user : User,
        post : Post,
        about : About,
        shielded : Admin
    }

    let controller = location.pathname.split("/")[1];

</script>


