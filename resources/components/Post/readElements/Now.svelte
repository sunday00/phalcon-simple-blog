<svelte:window bind:scrollY={y}/>
<div class="progress">
    <ul>
        {#each buttons as dom}
            <li class:unpass="{ dom.offsetTop > y }" class:pass="{ dom.offsetTop < y }">
                <a href="#" title="{dom.innerHTML}" on:click|preventDefault={goY(dom.offsetTop)}>
                    <i class="{`bg-${theme}-secondary`}">{dom.innerHTML}</i>
                </a>
            </li>
        {/each}
    </ul>
</div>

<script>
    export let doms;
    export let theme;
    let y;
    let buttons = [];

    makeDomsLoad();
    function makeDomsLoad(){
        if( !doms.length ){
            setTimeout(() => {
                makeDomsLoad();
            }, 300);
        } else {
            Array.from(doms).forEach((dom) => {
                if( dom.classList.contains('sub-title') ) buttons.push(dom);
                else if ( dom.classList.contains('article-divide') ) buttons.push(dom);
            });
            buttons = buttons;
        }
    }

    function goY(y){
        window.scrollTo(0, y - 30);
    }

</script>

<style lang="scss" type="text/scss" >
    .progress{
        position: fixed;
        bottom: 10px;
        left: 20px;

        ul{
            display: block;
            width: 150px;
        }

        i{
            display: block;
            width: 80%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding: 3px;
            color: white;
            margin: 5px auto;
            border-radius: 10px;
            text-align: center;
        }

        ul li.pass + li.unpass i, ul li.unpass:first-child i{
            width: 100%;
        }
    }
</style>