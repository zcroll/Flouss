<template>
    <div>
        <!-- Other content -->
        <FavoriteButton
            :model-id="career.id"
            model-type="career"
            :initial-is-favorited="career.is_favorite"
        />
        <!-- Other content -->
    </div>
</template>

<script>
import FavoriteButton from '@/Components/helpers/FavoriteButton.vue'

export default {
    components: {
        FavoriteButton
    },
    props: {
        career: Object
    }
}
</script>
