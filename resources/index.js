import App from './components/App.svelte';

const app = new App({
    target: document.querySelector('.content'),
    props: {
        csrf : document.querySelector('[name="csrf_token"]').dataset,
        theme : document.querySelector('[name="theme"]').content
    },
});

export default app;