<template>
  <div>
    <div class="w-11 h-64 overflow-hidden">
      <img src alt="userbg image" class="object-cover w-full" />
    </div>
  </div>
</template>
<script>
export default {
  name: "Show",
  data: () => {
    return {
      user: null,
      posts: null,
      loading: true
    };
  },
  mounted() {
    axios
      .get("/api/users/" + this.$route.params.userId)
      .then(res => {
        this.user = res.data;
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => {
        this.loading = false;
      });
    axios
      .get("/api/posts/" + this.$route.params.userId)
      .then(res => {
        this.posts = res.data;
      })
      .catch(err => {
        console.log("Unable to facth posts");
        this.loading = !this.loading;
      });
  }
};
</script>
