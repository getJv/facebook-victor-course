<template>
  <div class="flex flex-col items-center py-4" v-if="newsPostsStatus === 'success'">
    <NewPost />
    <p v-if="newsPostsStatus === 'loading'">Loading posts...</p>
    <Post v-else v-for="(post,postKey) in newsPosts.data" :key="postKey" :post="post" />
  </div>
</template>
<script>
import NewPost from "../components/NewPost";
import Post from "../components/Post";
import { mapGetters } from "vuex";
export default {
  name: "NewsFeed",
  components: {
    NewPost,
    Post
  },
  computed: {
    ...mapGetters({
      newsPosts: "posts",
      newsPostsStatus: "newsPostsStatus"
    })
  },

  mounted() {
    this.$store.dispatch("fetchNewsPosts");
  }
};
</script>
