<div>
    {#if res === true}
        <h1 in:fly="{{x:-300, duration:400}}">Phalog</h1>
    {/if}

    <hr />

    {#if res === true}
    <div class="text-right pr-10 mt-10 text-lg" in:fly="{{x:-600, duration:800}}">
        Welcome!
        Until now, <span>{visitCount}</span> people dropped by here.
    </div>
    {/if}

    {#if res === true}
    <div class="text-right pr-10 mt-10 text-lg" in:fly="{{x:-900, duration:1200}}">
        <span>{articleCount}</span> articles are posted.
    </div>
    {/if}

    <div class="mx-3 my-10 text-center">
        {#each tags as tag}
            <Tag tagName="{tag}" mode="read" theme="{theme}">{tag}</Tag>
        {/each}
    </div>

    <div>
        <fieldset class="border border-2 w-5/6 mx-auto py-6 px-10 m-6 relative">
            <legend>Theme</legend>
            <p>
                <label class="text-2xl">
                    <input type="radio" name="theme" id="theme-rustic"
                           class="form-radio text-indigo-600 w-6 h-6"
                           bind:group={checkedTheme} value="{'rustic'}"
                           on:click={setTheme}
                    > Rustic (default)
                    <span class="absolute"><i></i><i></i><i></i><i></i><i></i></span>
                </label>
            </p>
            <p>
                <label class="text-2xl">
                    <input type="radio" name="theme" id="theme-cyber"
                           class="form-radio text-indigo-600 w-6 h-6"
                           bind:group={checkedTheme} value="{'cyber'}"
                           on:click={setTheme}
                    > Cyber
                    <span class="absolute"><i></i><i></i><i></i><i></i><i></i></span>
                </label>
            </p>
        </fieldset>
    </div>
</div>

<script>
    import { flip } from 'svelte/animate';
    import { quintOut } from 'svelte/easing';
    import { fly } from 'svelte/transition';
    import { onMount } from 'svelte';

    import Tag from '../Post/createElements/Tag.svelte'

    export let theme;
    export let res = false;
    export let visitCount = 0;
    export let articleCount = 0;
    export let tags = [];

    let checkedTheme = 'rustic';

    onMount(async () => {
        res = true;

        axios.get('/api/v1/getTheme').then((response) => {
            checkedTheme = response.data.theme;
        });

        if ( getCookie('visited') && getCookie('posts') && getCookie('tags')){
            visitCount = getCookie('visited');
            articleCount = getCookie('posts');
            tags = getCookie('tags').split(',');
            return ;
        }

        axios.get('/api/v1/info').then((response) => {
            visitCount = response.data.visit;
            articleCount = response.data.post;
            tags = response.data.tags;

            let date = new Date();
            date.setTime(Date.now() + 1000 * 60 * 60 * 24);
            document.cookie = `visited=${visitCount};expires=${date.toGMTString()};path=/;SameSite=Strict;`;
            document.cookie = `posts=${articleCount};expires=${date.toGMTString()};path=/;SameSite=Strict;`;
            document.cookie = `tags=${tags};expires=${date.toGMTString()};path=/;SameSite=Strict;`;
        });
    });

    function getCookie(name){
        let cookies = document.cookie.split("; ");
        let cookieVal;
        cookies.forEach((cookie) => {
            let [k, v] = cookie.split('=');
            if (k === name) cookieVal = v;
        });
        return cookieVal;
    }

    function setTheme(e){
        let date = new Date();
        date.setTime(Date.now() + 1000 * 60 * 60 * 24 * 365);
        document.cookie = `theme=${e.target.value};expires=${date.toGMTString()};path=/;SameSite=Strict;`;
        checkedTheme = e.target.value;
        location.reload();
    }

</script>

<style lang="scss" type="text/scss">
    h1 {
        text-align: right;
        padding-right: 40px;
        padding-top: 40px;
        font-size: 3rem;
        font-weight: bold;
        font-family: fredric;
    }

    label span i {
        display: inline-block;
        width: 25px;
        height: 25px;
    }

    #theme-rustic + span{
        right: 20px;

        i:nth-child(1){
            background-color: #49a79f;
        }
        i:nth-child(2){
            background-color: #3a5f6b;
        }
        i:nth-child(3){
            background-color: #b2f5ea;
        }
        i:nth-child(4){
            background-color: #ffb87a;
        }
        i:nth-child(5){
            background-color: #ffe28e;
        }
    }

    #theme-cyber + span{
        right: 20px;

        i:nth-child(1){
            background-color: #01100e;
        }
        i:nth-child(2){
            background-color: #b100ff;
        }
        i:nth-child(3){
            background-color: #b26bef;
        }
        i:nth-child(4){
            background-color: #7affd0;
        }
        i:nth-child(5){
            background-color: #28ff00;
        }
    }
</style>