<div class="w-full max-w-ms h-full mx-auto flex-1">
<!--    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="post" action="/user/sign">-->
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="post" action="/user/sign" on:submit|preventDefault="{trySignIn}">
        <input type="hidden" name="{csrf.name}" value="{csrf.value}">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Email
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" class:border-red-500={ error_title }
                   id="username" type="text" placeholder="Username" name="email" bind:value={email}>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" class:border-red-500={ error_title }
                   id="password" type="password" placeholder="******************" name="password" bind:value={password}>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Sign In
            </button>
            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
                Forgot Password?
            </a>
        </div>
    </form>
    {#if error_title}
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold" contenteditable="true" bind:innerHTML={error_title}></strong>
        <span class="block w-3/4 sm:w-5/6 " contenteditable="true" bind:innerHTML={error_message}></span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-5">
            {#if error_title === 'CSRF' }
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-full" on:click={refresh}>Refresh</button>
            {:else}
            <svg on:click={closeError} class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
            {/if}
        </span>
    </div>
    {/if}
</div>

<script>
    let csrf = document.querySelector('[name="csrf_token"]').dataset;
    let error_title;
    let error_message;

    let email;
    let password;

    let trySignIn = (e) => {
        const formData = new FormData();
        formData.append(csrf.name, csrf.value);
        formData.append('email', email);
        formData.append('password', password);
        axios.post("/api/v1/user/signIn", formData, {
            headers: { 'content-type': 'multipart/form-data' },
        }).then( response => {
            if( response.data && response.data.error === 'csrf' ){
                error_title = "CSRF";
                error_message = " is not valid. Refresh The page in a second to Receive new token";
                let delay = setTimeout(()=>{
                    location.reload();
                }, 5000);
            } else if ( response.data && response.data.error === 'not permitted' ) {
                csrf.name = response.data.newCsrfName;
                csrf.value = response.data.newCsrfValue;
                error_title = response.data.error;
                error_message = response.data.msg;
            } else if ( response.data && response.data.status === 'ok' ) {
                error_title = null;
                location.href = response.data.action;
            }
        });
    }

    let refresh = () => {
        location.reload();
    }

    let closeError = () => {
        error_title = null;
    }
</script>