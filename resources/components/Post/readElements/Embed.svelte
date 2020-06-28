<div class="youtube-wrap my-4">
    <iframe
        class="embed"
        src="{data.embed}"
        frameborder="0"
        allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen>
    </iframe>
    {#if data.caption}
        <p class="text-center">[ {data.caption} ]</p>
    {/if}
</div>

<script>
    export let data;
</script>

<style>
    iframe.embed{
        width: 100%;
        min-height: 400px;
    }
</style>