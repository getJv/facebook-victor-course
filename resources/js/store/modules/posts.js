const state = {
    newsPosts: null,
    newsPostsStatus: null
};
const getters = {
    newsPosts: state => {
        return state.newsPosts;
    },
    newsPostsStatus: state => {
        return state.newsPostsStatus;
    }
};
const actions = {
    fetchNewsPosts({ commit, state }) {
        commit("setPostsStatus", "loading");
        axios
            .get("/api/posts")
            .then(res => {
                commit("setPosts", res.data);
                commit("setPostsStatus", "success");
            })
            .catch(err => {
                console.log("Unable to facth posts");
                commit("setPostsStatus", "error");
            });
    }
};
const mutations = {
    setPosts(state, posts) {
        state.newsPosts = posts;
    },
    setPostsStatus(state, status) {
        state.newsPostsStatus = status;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
