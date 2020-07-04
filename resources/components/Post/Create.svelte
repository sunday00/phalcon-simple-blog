<div>
    <form class="" on:submit|preventDefault={submit}>
        <input class="bg-white focus:outline-none focus:shadow-outline border border-gray-300 rounded-lg py-2 px-4 my-4 block w-full appearance-none leading-normal"
               name="title"
               type="text"
               bind:value={title} />
        <div id="editorjs"></div>
        <div class="tags my-4 mx-4">
            {#each tags as tag}
                <Tag theme={theme} on:delTag={delTag} tagName="{tag}" mode="create">{tag}</Tag>
            {/each}
        </div>
        <div>
            tags : to add tag, input some text and press enter key.
            <input type="text" on:keydown={addTag}
                   class="bg-white focus:outline-none focus:shadow-outline border border-gray-300 rounded-lg py-1 px-3 my-4 block w-2/5 appearance-none leading-normal"
            />
        </div>
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

    import Tag from './createElements/Tag.svelte';

    export let csrf;
    export let theme;
    export let mode = 'create';

    let title = '';
    let content = '';
    let files = [];
    let originalFiles = [];
    let tags = [];

    class Image extends ImageTool{
        constructor({data, api, config}) {
            super({data, api, config});
        }
        updated(){
            if( files.indexOf(this.data.file) < 0 ){
                files.push(this.data.file);
                console.log(this);
            }
        }

        removed(){
            let file = this.data.file;
            files.splice(files.indexOf(file), 1);
            if ( originalFiles.indexOf(file) < 0){
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

    function sendCreate(outputData) {
        const url = `/api/v1/post/store`;
        const formData = new FormData();
        formData.append(csrf.name, csrf.value);
        formData.append('title', title);
        formData.append('content', encodeURIComponent( JSON.stringify( (outputData.blocks) ) ));
        formData.append('tags', JSON.stringify(tags));
        formData.append('files', JSON.stringify(files));
        axios.post(url, formData, {
            headers: { 'content-type': 'multipart/form-data' },
        }).then(response => {
            console.log( response );
            location.href = `/post/read/${response.data.id}`;
        });
    }

    function sendEdit(outputData) {
        const url = `/api/v1/post/update/${location.href.split('/').pop()}`;
        let data = {
            csrf,
            title,
            blocks: outputData.blocks,
            tags,
            originalFiles,
            files
        }
        axios.put(url, data, {
            headers: { 'content-type': 'multipart/form-data' },
        }).then(response => {
            console.log( response );
            location.href = `/post/read/${response.data.id}`;
        });
    }

    const editor = new EditorJS({
        autofocus: mode === 'create',
        logLevel: 'ERROR',
        placeholder: mode === 'create' ? 'Make something amazing' : null,
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

    editor.isReady.then(() => {
        if( mode === 'edit' ){
            axios.get('/api/v1/post/read/' + location.href.split('/').pop())
                .then((response) => {
                    title = response.data.title;
                    let blocks = JSON.parse(response.data.body);
                    blocks.forEach((block) => {
                        editor.blocks.insert(block.type, block.data);
                        if( block.type === 'image' ){
                            originalFiles.push(block.data.file);
                            files.push(block.data.file);
                        }
                    });
                    response.data.tags.forEach((tagObj) => {
                        tags.push( tagObj.title );
                    });

                    tags = tags;
                    editor.blocks.delete(0);
                });
        }
    });

    function addTag (e) {
        if(e.key.toLowerCase() === 'enter'){
            e.preventDefault();

            if( tags.indexOf(e.target.value) < 0) {
                tags.push( e.target.value );
                tags = tags;
            }

            e.target.value = '';
        }
    }

    function delTag(e){
        tags.splice(tags.indexOf(e.detail.dataset.name), 1);
        tags = tags;
    }

    function submit (e) {
        editor.save().then((outputData) => {
            if (mode === 'create')  sendCreate(outputData);
            else sendEdit(outputData);
        }).catch((error) => {
            console.log('Saving failed: ', error)
        });
    }
</script>