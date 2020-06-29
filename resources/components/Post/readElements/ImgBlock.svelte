<div class="img-wrap my-4">
    <img src="{data.file.url}" alt="" class:w-full="{data.stretched}" class="{data.withBackground ? `bg-${theme}-gray` : ''}"/>
    {#if data.caption}
        <p class="text-center" theme="{theme}">[ {data.caption} ]</p>
    {/if}
</div>
<script>
export let data;
export let theme;
</script>