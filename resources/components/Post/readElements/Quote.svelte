<div class="quote-wrap">
    <div class="quote-body bg-{theme}-gray p-3 border-{theme}-secondary border-0 border-l-4 ">
        {data.text}
    </div>
    {#if data.caption}
        <p class="text-center">[ {data.caption} ]</p>
    {/if}
</div>

<script>
    export let data;
    export let theme;
</script>

<style>

</style>