<div>
    <form action="">
        <input class="bg-white focus:outline-none focus:shadow-outline border border-gray-300 rounded-lg py-2 px-4 my-4 block w-full appearance-none leading-normal"
               type="text" />
        <div id="editorjs"></div>
    </form>
</div>

<script>
    import EditorJS from '@editorjs/editorjs';
    import Header from '@editorjs/header';
    import ImageTool from '../vendor/imageUpload';

    const editor = new EditorJS({
        autofocus: true,
        tools: {
            header: {
                class: Header,
                inlineToolbar: ['link']
            },
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '/image/upload/file',
                        byUrl: '/image/upload/url',
                    }
                }
            }
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
        }
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