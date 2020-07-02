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
    <div class="flex">
        <a href="/post/edit/{location.href.split('/').pop()}"
           class="bg-{ theme }-primary hover:bg-{ theme }-accent text-white font-bold py-2 px-4 mt-4 mr-4 rounded">
            Edit
        </a>
        <form action="/post/delete/{location.href.split('/').pop()}" on:submit|preventDefault={deleteAsk}>
            <input type="submit" value="Delete"
                   class="bg-{ theme }-primary hover:bg-{ theme }-accent text-white font-bold py-2 px-4 mt-4 rounded" />
        </form>
    </div>
</div>
{#if bodyElement && bodyElement.children}
    <Now doms="{bodyElement.children}" theme="{theme}"></Now>
{/if}

{#if error_title}
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 my-4 rounded relative" role="alert">
        <strong class="font-bold" contenteditable="true" bind:innerHTML={error_title}></strong>
        <span class="block w-3/4 sm:w-5/6 " contenteditable="true" bind:innerHTML={error_message}></span>
        {#if error_title === 'danger'}
        <span><button class="bg-red-500 text-white px-4 py-3 my-4 rounded" on:click|preventDefault={deletePost}>OK</button></span>
        {/if}
        <span class="absolute top-0 bottom-0 right-0 px-4 py-5">
            <svg on:click={closeError} class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </span>
    </div>
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

    let error_title;
    let error_message;

    axios.get('/api/v1/post/read/' + location.href.split('/').pop())
        .then((response) => {
            title = response.data.title;
            blocks = JSON.parse(response.data.body);
    });

    let bodyElement;

    function deleteAsk() {
        error_title = "danger";
        error_message = "delete permanently";
    }

    function deletePost(){
        axios.delete('/api/v1/post/delete/' + location.href.split('/').pop())
                .then((response) => {
                    if(response.data.status === 'success'){
                        location.href = "/post";
                    }else if ( response.data && response.data.error === 'already not exists' ) {
                        error_title = response.data.error;
                        error_message = response.data.msg;
                        setTimeout(() => {
                            location.href = "/post";
                        }, 3000);
                    }
                });
    }

    let refresh = () => {
        location.reload();
    }

    let closeError = () => {
        error_title = null;
    }

</script>

<style type="text/scss">

</style>