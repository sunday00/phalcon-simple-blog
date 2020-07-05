<div class="img-wrap my-4">
    <img src="{data.file.url}" alt="" class="{data.withBackground ? `bg-${theme}-gray` : ''}"
         class="{data.stretched ? `w-full` : 'w-1/3 mx-auto block'}"/>
    {#if data.caption}
        <p class="text-center" theme="{theme}">[ {data.caption} ]</p>
    {/if}
</div>
<script>
export let data;
export let theme;
</script>