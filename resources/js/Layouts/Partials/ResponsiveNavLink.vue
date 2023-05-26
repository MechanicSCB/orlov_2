<script>
export default {
    props:['href'],
}
</script>
<template>
    <Link :href="href" class="hover:underline border-b border-gray-700 w-full py-3 text-sm">
        <slot />
    </Link>
</template>


