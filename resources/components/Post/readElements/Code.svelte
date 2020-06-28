<svelte:head>
    {@html atomOneDark}
</svelte:head>

<Highlight language={lang} {code} />

<script>
    import { Highlight } from 'svelte-highlight';
    import { php, java, javascript, python } from 'svelte-highlight/languages';
    import { atomOneDark } from 'svelte-highlight/styles';

    export let data;

    const Lang = {
        php, java, javascript, python
    }

    let lang;
    let code = data.code;

    if ( /^\/\/([\w,\s-]+)\.php/.test(data.code) ) {
        lang = Lang['php'];
    } else if ( /^\/\/([\w,\s-]+)\.java/.test(data.code) || /^\/\/([\w,\s-]+)\.class/.test(data.code) ) {
        lang = Lang['java'];
    } else if ( /^\/\/([\w,\s-]+)\.py/.test(data.code) ) {
        lang = Lang['python'];
    } else if ( /^\/\/([\w,\s-]+)\.js/.test(data.code) ) {
        lang = Lang['javascript'];
    } else {
        lang = Lang['php'];
    }

</script>