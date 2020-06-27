<div>
    <form action="" class="">
        <input class="bg-white focus:outline-none focus:shadow-outline border border-gray-300 rounded-lg py-2 px-4 my-4 block w-full appearance-none leading-normal"
               name="title"
               type="text" />
        <div id="editorjs"></div>
        <div class="flex flex-col">
            <input type="submit" value="submit" class="self-end bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded" />
        </div>
    </form>
</div>

<script>
    import EditorJS from '@editorjs/editorjs';
    import Header from '@editorjs/header';
    import ImageTool from '../vendor/imageUpload.js';
    import Embed from '../vendor/embed.js';

    let csrf = document.querySelector('[name="csrf_token"]').dataset;
    let mode = 'create';
    let content;
    let files = [];

    class Image extends ImageTool{
        constructor({data, api, config}) {
            super({data, api, config});
        }
        updated(){
            files.push(this.data.file.url);
            console.log(this.data);
        }

        removed(){
            let file = this.data.file.url;
            files.splice(files.indexOf(file), 1);
            if (mode === 'create'){
                const formData = new FormData();
                formData.append(csrf.name, csrf.value);
                formData.append('file', file);
                axios.post(`/image/delete`, formData, {
                    headers: { 'content-type': 'multipart/form-data' },
                }).then(response => {
                    console.log(response);
                });
            }
            console.log(file, files);
        }
    }

    const editor = new EditorJS({
        autofocus: true,
        logLevel: 'ERROR',
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