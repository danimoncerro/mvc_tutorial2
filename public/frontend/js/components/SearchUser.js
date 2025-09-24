const SearchUser = {
  name: 'SearchUser',
  emits: ['search-users', 'show-users'],
  template: `
    <div class="mb-3">
      <div class="col-md-3">
        <input
          v-model="search"
          type="text"
          class="form-control"
          placeholder="Caută utilizatori..."
        >
      </div>
    </div>
  `,
  setup(_, { emit }) {
    const { ref, watch } = Vue;

    const search = ref('');

    watch(search, (q) => {
      const s = q.trim();
      if (!s) emit('show-users');
      else emit('search-users', s);
    });

    return { search };
  }
};
