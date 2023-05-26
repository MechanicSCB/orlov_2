<template>
    <span>{{ comments_count ?? 0 }} {{ commentsStr}}</span>
</template>

<script>
export default {
    props:['comments_count'],
    computed:{
        commentsStr(){
            let cnt = this.comments_count?.toString() ?? '0';
            if(cnt.slice(-2,-1) === '1') return 'комментариев';
            if(cnt.slice(-1) === '1') return 'комментарий';
            if(cnt.slice(-1) === '2') return 'комментария';
            if(cnt.slice(-1) === '3') return 'комментария';
            if(cnt.slice(-1) === '4') return 'комментария';
            return 'комментариев';
        }
    }
}
</script>
