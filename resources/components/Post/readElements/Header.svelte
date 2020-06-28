{@html el.outerHTML}

<script>
    export let level;
    export let text;
    let el = document.createElement(`h${level}`);
    el.className = 'ce-header sub-title';
    el.innerHTML = text;
</script>