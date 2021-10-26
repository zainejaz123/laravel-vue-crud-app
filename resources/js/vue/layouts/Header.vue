<template>
    <header class="bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center">
                        <img :src="logo" style="max-width: 200px;">
                        <a href="#" class="anchor ml-3">About Us</a>
                        <a href="/applications" class="anchor ml-3">Applications</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    data() {
        return{
            logo:'img/logo-social.png',
        }
    },
}
</script>