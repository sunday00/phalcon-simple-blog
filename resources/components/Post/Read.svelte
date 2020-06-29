<div>
    <h1 class="ce-header mb-4 font-bold text-right border-b-2" style="margin-bottom: 20px;">{title}</h1>
    <div class="article body flex flex-col mb-10" bind:this={bodyElement}>
        {#each blocks as block}
            {#if block.type === 'paragraph'}
                <p>{@html block.data.text}</p>
            {:else if block.type === 'header'}
                <Header level="{block.data.level}" text="{block.data.text}"></Header>
            {:else if block.type === 'image'}
                <ImgBlock data="{block.data}" theme="{theme}"></ImgBlock>
            {:else if block.type === 'code'}
                <Code data="{block.data}"></Code>
            {:else if block.type === 'delimiter'}
                <p class="article-divide font-extrabold text-lg text-center my-4" style="letter-spacing: 0.6rem;">* * *</p>
            {:else if block.type === 'quote'}
                <Quote data="{block.data}" theme="{theme}"></Quote>
            {:else if block.type === 'embed'}
                <Embed data="{block.data}"></Embed>
            {:else }
                <p class="text-red-300">{block.type}</p>
            {/if}
        {/each}
    </div>
    <div>
        <a href="/post/edit/{location.href.split('/').pop()}"
           class="bg-{ theme }-primary hover:bg-{ theme }-accent text-white font-bold py-2 px-4 mt-4 rounded">
            Edit
        </a>
    </div>
</div>
{#if bodyElement && bodyElement.children}
    <Now doms="{bodyElement.children}" theme="{theme}"></Now>
{/if}
<script>
    import Now from './readElements/Now.svelte';

    import Header from './readElements/Header.svelte';
    import ImgBlock from './readElements/ImgBlock.svelte';
    import Code from './readElements/Code.svelte';
    import Quote from './readElements/Quote.svelte';
    import Embed from './readElements/Embed.svelte';

    export let theme;

    let title;
    let blocks = [];

    axios.get('/api/v1/post/read/' + location.href.split('/').pop())
        .then((response) => {
            title = response.data.title;
            blocks = JSON.parse(response.data.body);
    });

    let bodyElement;
</script>

<style type="text/scss">

</style>