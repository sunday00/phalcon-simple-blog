<div class="img-wrap my-4">
    <img src="{data.file.url}" alt="" class="{classname}"/>
    {#if data.caption}
        <p class="text-center" theme="{theme}">[ {data.caption} ]</p>
    {/if}
</div>
<script>
export let data;
export let theme;

let classname = '';

console.log(data);

if( data.withBackground ){
    classname += ` bg-${theme}-gray`;
}

if( data.stretched ){
    classname += ` w-full`;
} else {
    classname += 'w-2/3 mx-auto block';
}

</script>