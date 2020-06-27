<div>
    <form class="" on:submit|preventDefault={submit}>
        <input class="bg-white focus:outline-none focus:shadow-outline border border-gray-300 rounded-lg py-2 px-4 my-4 block w-full appearance-none leading-normal"
               name="title"
               type="text"
               bind:value={title} />
        <div id="editorjs"></div>
        <div class="flex flex-col">
            <input type="submit" value="submit" class="self-end bg-{theme}-primary hover:bg-{theme}-accent text-white font-bold py-2 px-4 mt-4 rounded" />
        </div>
    </form>
</div>

<script>
    import EditorJS from '@editorjs/editorjs';
    import Header from '@editorjs/header';
    import ImageTool from '../vendor/imageUpload.js';
    import Embed from '../vendor/embed.js';
    import Code from '../vendor/code.js';
    import InlineCode from '../vendor/inlinecode.js';
    import Delimiter from '../vendor/delimiter.js';
    import Marker from '../vendor/marker.js';
    import Quote from '../vendor/quote.js';

    let theme = document.querySelector('[name="theme"]').content;
    let csrf = document.querySelector('[name="csrf_token"]').dataset;
    let mode = 'create';
    let title = '';
    let content = '';
    let files = [];

    class Image extends ImageTool{
        constructor({data, api, config}) {
            super({data, api, config});
        }
        updated(){
            if( files.indexOf(this.data.file.url) < 0 ){
                let file = {};
                files.push(this.data.file);
                console.log(this);
            }
        }

        removed(){
            let file = this.data.file;
            files.splice(files.indexOf(file), 1);
            if (mode === 'create'){
                const formData = new FormData();
                formData.append(csrf.name, csrf.value);
                formData.append('file', file.url);
                axios.post(`/image/delete`, formData, {
                    headers: { 'content-type': 'multipart/form-data' },
                }).then(response => {
                    console.log(response);
                });
            }
        }
    }

    const editor = new EditorJS({
        autofocus: true,
        logLevel: 'ERROR',
        placeholder: 'Make something amazing',
        tools: {
            header: {
                class: Header,
                inlineToolbar: ['link']
            },
            image: {
                class: Image,
                config: {
                    endpoints: {
                        byFile: '/image/upload/file',
                        byUrl: '/image/upload/url',
                    },
                }
            },
            embed: {
                class: Embed,
                config: {
                    services: {
                        youtube: true,
                        coub: true
                    },
                }
            },
            code: Code,
            inlineCode: InlineCode,
            delimiter: Delimiter,
            marker: Marker,
            quote: Quote,
        },
        i18n: {
            messages: {
                ui: {
                    // Translations of internal UI components of the editor.js core
                },
                toolNames: {
                    // Section for translation Tool Names: both block and inline tools
                },
                tools: {
                    // Section for passing translations to the external tools classes
                    // The first-level keys of this object should be equal of keys ot the 'tools' property of EditorConfig
                },
                blockTunes: {
                    // Section allows to translate Block Tunes
                },
            }
        },
        data: {}
    });

    function submit (e) {
        editor.save().then((outputData) => {
            const formData = new FormData();
            formData.append(csrf.name, csrf.value);
            formData.append('title', title);
            formData.append('content', JSON.stringify(outputData.blocks));
            formData.append('files', JSON.stringify(files));
            axios.post(`/api/v1/post/store`, formData, {
                headers: { 'content-type': 'multipart/form-data' },
            }).then(response => {
                location.href = `/post/search/${response.data.id}`
            });
        }).catch((error) => {
            console.log('Saving failed: ', error)
        });
    }

</script>

<style type="text/scss">
    #editorjs{
        background-color: #fff;
        padding: 40px;

        h1.ce-header{
            font-size: 2rem;
        }
    }
</style>