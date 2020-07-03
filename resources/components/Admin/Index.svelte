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


<script>
    import { onMount } from 'svelte';

    let checkedTheme = 'rustic';

    onMount(async () => {
        axios.get('/api/v1/getTheme').then((response) => {
            checkedTheme = response.data.theme;
        });
    });

    function setTheme(e){
        let date = new Date();
        date.setTime(Date.now() + 1000 * 60 * 60 * 24 * 365);
        document.cookie = `theme=${e.target.value};expires=${date.toGMTString()};path=/;SameSite=Strict;`;
        checkedTheme = e.target.value;
        location.reload();
    }
</script>

<style type="text/scss" lang="scss">
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