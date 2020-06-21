{#if location.pathname == '/' }
<Main></Main>
{:else if location.pathname.startsWith('/post') }
<Post></Post>
{/if}

<script>
    import Main from './Main/Index.svelte';
    import Post from './Post/Index.svelte';

</script>


