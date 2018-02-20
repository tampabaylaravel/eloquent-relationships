# Getting Comfortable with Eloquent Relationships

### What are relationships?
  * One to one: has one, belongs to (one)
  * One to many: has many, belongs to (one)
  * Many to many: belongs to many (pivot table, users/permissions)
  * Don't always need the inverse relation defined
  * Intentionally skipping polymorphic relations

### What is different with and without the parentheses?
  * `$user->posts()` is a relationship object, which is also a query builder object
  * What is Query Builder?
    * `DB::where()->get()`, `User::where()->get()`
  * `$user->posts` is a magic accessor that calls the object behind the scenes (if not already resolved)
  * Mapping between method and property:
    * `$user->posts()->get() === $user->posts`
  * Can also do fun stuff such as adding condidtions on relations:
    * `$user->recentPosts()` returns `$this->posts()->where();`

### Creating Related Records
* Using related ID
* Going through the relationships:
  * `$user->posts()->save($post)`
  * `$user->posts()->create([])`
  * `$post->user()->associate($user)->save()`
  * `$user->posts()->saveMany($posts)` or `$user->permissions()->attach()`
* Gotchas: forgetting to use the parentheses

### Solving the N+1 problem
* Lazy Loading
* Eager Loading (`with()` and `load()`)
  * Nested eager loading
* With Count
  * `withCount('posts')` gives `$user->posts_count`

---

### Advanced Features (for another day):
* Polymorphic Relations
* Has
* Where Has
* Has Many Through
* Query Scopes
* Nested Eager Loading
* Updating parents timestamps
* Accessors
* Mutators
* Casts and Dates
