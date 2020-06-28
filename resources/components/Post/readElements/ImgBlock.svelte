<div class="img-wrap my-4">
    <img src="{data.file.url}" alt="" class="{data.stretched ? 'w-full' : ''}" />
    {#if data.caption}
        <p class="text-center">[ {data.caption} ]</p>
    {/if}
</div>
<script>
export let data;
</script>