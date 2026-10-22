# TaskFlow — Candidate Interview Notes & Architectural Review

## 1. Project Architecture
The project strictly follows standard Laravel MVC conventions without unnecessary design patterns or service-layer bloat:
- **Routing (`routes/web.php`)**: Clean RESTful resource routing via `Route::resource('tasks', TaskController::class)` with named routes and route model binding. Root URL (`/`) redirects directly to `/tasks`.
- **Controller (`app/Http/Controllers/TaskController.php`)**: A thin resource controller adhering to Single Responsibility. Handles HTTP requests, coordinates query builder filters, and dispatches data to views or redirects with session flash banners.
- **Form Request (`app/Http/Requests/TaskRequest.php`)**: Centralized server-side validation and authorization for both store and update actions. Isolates validation logic outside controllers.
- **Model (`app/Models/Task.php`)**: Eloquent model encapsulating `$fillable` mass-assignment rules, attribute casting (`due_date` as date), and centralized domain constants (`PRIORITIES`, `STATUSES`).
- **Views & UI (`resources/views/`)**: Blade templates styled with Bootstrap 5.3.3 and Bootstrap Icons. Uses a master layout (`layouts/app.blade.php`), a shared form partial (`tasks/form.blade.php`), reusable alert banners (`partials/flash.blade.php`), and reusable anonymous Blade components (`components/priority-badge.blade.php`, `components/status-badge.blade.php`).

---

## 2. Migration and Model
- **Migration (`database/migrations/*_create_tasks_table.php`)**:
  - `id`: Auto-incrementing primary key.
  - `title`: `string(255)`, non-nullable.
  - `description`: `text()`, nullable for optional task details.
  - `priority`: `string(20)`, default `'Medium'`.
  - `status`: `string(30)`, default `'Pending'`.
  - `due_date`: `date()`, nullable.
  - `timestamps()`: Tracks `created_at` and `updated_at`.
- **Model (`app/Models/Task.php`)**:
  - Defines `PRIORITIES = ['Low', 'Medium', 'High']` and `STATUSES = ['Pending', 'In Progress', 'Completed']` to establish a single source of truth across forms, migrations, and validation.
  - Defines `protected $fillable = ['title', 'description', 'priority', 'status', 'due_date']` to strictly restrict attributes acceptable for mass assignment.
  - Implements `protected function casts(): array` to cast `due_date` to a Carbon date instance for fluent date formatting and overdue checks (`$task->due_date?->isPast()`).

---

## 3. How CRUD Operations Work
- **Create**:
  1. `GET /tasks/create` (`tasks.create`): Instantiates a blank `Task` model pre-populated with default priority `'Medium'` and status `'Pending'`.
  2. `POST /tasks` (`tasks.store`): Validates input via `TaskRequest`, calls `Task::create($request->validated())`, and redirects to `tasks.index` with a `'success'` session flash.
- **Read**:
  1. `GET /tasks` (`tasks.index`): Displays paginated tasks with search/filtering options and real-time dashboard aggregate counts (`Total`, `Pending`, `In Progress`, `Completed`).
  2. `GET /tasks/{task}` (`tasks.show`): Resolves the task via automatic route model binding (`Task $task`) and displays its full details, audit timestamps, and overdue indicators.
- **Update**:
  1. `GET /tasks/{task}/edit` (`tasks.edit`): Pre-fills the shared form partial with current model attributes.
  2. `PUT /tasks/{task}` (`tasks.update`): Validates input via `TaskRequest`, invokes `$task->update($request->validated())`, and redirects back to `tasks.index` with a `'success'` flash.
- **Delete**:
  1. `DELETE /tasks/{task}` (`tasks.destroy`): Form submission utilizes CSRF token (`@csrf`) and HTTP verb spoofing (`@method('DELETE')`) with a JavaScript confirmation dialog. Deletes the model and redirects with a `'success'` flash.

---

## 4. Combined Search and Filtering
Implemented dynamically directly inside the Eloquent Query Builder within `TaskController::index`:
```php
$query = Task::query();

// Search by title (safely escaping SQL LIKE wildcards)
if ($request->filled('search')) {
    $searchTerm = trim((string) $request->input('search'));
    $query->where('title', 'like', '%' . addcslashes($searchTerm, '%_\\') . '%');
}

// Whitelist-validated status filter
if ($request->filled('status') && in_array($request->status, Task::STATUSES, true)) {
    $query->where('status', $request->status);
}

// Whitelist-validated priority filter
if ($request->filled('priority') && in_array($request->priority, Task::PRIORITIES, true)) {
    $query->where('priority', $request->priority);
}

// Order by latest and preserve all query strings during pagination
$tasks = $query->latest('id')->paginate(10)->withQueryString();
```
- **Preserving Query Strings**: `withQueryString()` appends active `search`, `status`, and `priority` query parameters to all pagination links.
- **SQL Wildcard Safety**: `addcslashes($searchTerm, '%_\\')` ensures literal searches for characters like `%` or `_` do not behave as wildcard operators.
- **Tamper Protection**: Input parameters for `status` and `priority` are checked against `Task::STATUSES` and `Task::PRIORITIES` whitelists; unexpected or invalid values are safely ignored rather than crashing the query.
- **Empty State**: If zero records match, the view displays a clean empty state with a "Clear Filters" action.

---

## 5. Validation and Mass-Assignment Protection
- **Form Request (`TaskRequest`)**:
  - `authorize()` returns `true` (no authentication restrictions in this skill test scope).
  - Validation rules:
    - `'title' => ['required', 'string', 'max:255']`
    - `'description' => ['nullable', 'string']`
    - `'priority' => ['required', Rule::in(Task::PRIORITIES)]`
    - `'status' => ['required', Rule::in(Task::STATUSES)]`
    - `'due_date' => ['nullable', 'date']`
  - Custom human-friendly error messages configured in `messages()`.
- **Mass-Assignment Defense**:
  - The controller strictly executes `Task::create($request->validated())` and `$task->update($request->validated())`.
  - Only fields explicitly declared in `TaskRequest::rules()` can pass into Eloquent.
  - Paired with the model's `$fillable` array, this guarantees defense-in-depth against malicious parameter injection.

---

## 6. How to Run the Tests
Tests are executed against an in-memory SQLite database (`:memory:`), ensuring total isolation from production data.

### Running via PHPUnit directly:
```bash
php vendor/phpunit/phpunit/phpunit
```

### Running via Artisan:
```bash
php artisan test
```

### Running the specific TaskManagementTest feature suite:
```bash
php vendor/phpunit/phpunit/phpunit tests/Feature/TaskManagementTest.php
```

### Test Coverage Summary:
- **17 total tests (66 assertions)** all passing with 100% success rate:
  1. `test_the_application_redirects_to_tasks_dashboard`: Root route redirects to `/tasks`.
  2. `test_task_listing_page_loads_successfully`: Dashboard header, branding, and task items render.
  3. `test_task_create_page_loads_successfully`: Form displays cleanly with required fields.
  4. `test_valid_task_can_be_created`: Data saves to database and redirects with success flash.
  5. `test_required_title_validation_works`: Empty title triggers validation error and halts insert.
  6. `test_title_max_length_validation_rejects_more_than_255_chars`: Strings >255 chars rejected.
  7. `test_invalid_priority_or_status_is_rejected`: Values outside enum whitelist are caught.
  8. `test_task_edit_page_loads_with_existing_values`: Form populates with current task values.
  9. `test_an_existing_task_can_be_updated`: Valid update persists to database.
  10. `test_a_task_can_be_deleted`: Record is purged from database.
  11. `test_missing_task_returns_404_not_found`: Non-existent IDs return clean 404 responses.
  12. `test_search_by_title_works`: Case-insensitive title substring search filtering.
  13. `test_status_and_priority_filters_work`: Independent and combined filtering.
  14. `test_empty_search_or_filter_results_display_clean_empty_state`: Empty state view displays.
  15. `test_pagination_preserves_query_parameters`: Pagination links retain search/filter queries.
  16. `test_task_details_page_loads_with_all_fields`: Details page displays audit fields and badges.
  17. `test_that_true_is_true`: Unit test verification.

---

## 7. Important Implementation Decisions and Limitations

### Key Decisions:
1. **Form Request over Controller Validation**: Extracted validation into `TaskRequest` to keep `TaskController` concise, readable, and DRY between store and update methods.
2. **Anonymous Blade Components**: Created `<x-priority-badge>` and `<x-status-badge>` in `resources/views/components/` to consolidate styling and icons into single reusable components.
3. **No Overengineered Repositories**: Applied query scopes directly through Eloquent. For a 1–2 hour assessment, repository patterns add redundant indirection without tangible benefit.
4. **Form Partial Reusability**: Factored out `tasks/form.blade.php` with `old('field', $task->field ?? 'default')` to unify create and edit forms while preserving user input upon validation errors.
5. **Dynamic Dashboard Counters**: Implemented real-time aggregate counts for `Total`, `Pending`, `In Progress`, and `Completed` tasks that reflect the database state independently of applied filters.

### Limitations & Next Steps (Production Roadmap):
- **Full-Text Indexing**: Title search uses SQL `LIKE %...%`. For large datasets (100k+ rows), full-text search (MySQL FULLTEXT or Laravel Scout with Meilisearch/Algolia) would be preferred.
- **Multi-Tenancy & Authentication**: The application does not include user accounts or authorization policies, as specified by the test scope. In a team SaaS setting, task ownership (`user_id` / `team_id`) and Laravel Policies would be added.
- **Soft Deletes**: Uses hard deletion (`$task->delete()`). Adding `SoftDeletes` would allow recovery of accidentally deleted tasks.

---

## 8. Five Likely Interview Questions & Technical Answers

### Q1: How does route model binding work in `TaskController::show(Task $task)`, and what happens if an invalid ID is requested?
**Answer**: Route model binding uses Laravel's reflection and routing parameter matching (`{task}` matching `$task`). Laravel automatically queries the database using `Task::findOrFail($id)`. If no matching record exists, Eloquent throws a `ModelNotFoundException`, which Laravel's exception handler translates into an HTTP 404 Not Found response, preventing unhandled exceptions or stack traces from reaching the user.

### Q2: Why did you choose a Form Request class (`TaskRequest`) instead of validating inline inside the controller?
**Answer**: A Form Request moves HTTP request authorization and validation logic out of the controller into a dedicated class. This honors the Single Responsibility Principle, keeps controller methods down to 3–4 lines of expressive code, makes validation rules reusable across both `store` and `update` actions, and simplifies independent unit/request testing.

### Q3: How does `withQueryString()` work during pagination, and why is it essential?
**Answer**: By default, Laravel's `paginate()` method generates page links that only include the `?page=X` parameter. Calling `withQueryString()` instructs the paginator to inspect the incoming request and append all active query string parameters (such as `search=feature`, `priority=High`, and `status=Pending`) to generated pagination URLs. Without it, clicking "Next" would reset the user's active search and filters.

### Q4: How does your implementation protect against mass-assignment vulnerabilities?
**Answer**: We implement two layers of defense:
1. In `TaskController`, we only pass `$request->validated()` to `Task::create()` and `$task->update()`, ensuring only keys defined in the form request rules are forwarded to Eloquent.
2. In `Task.php`, we define `$fillable = ['title', 'description', 'priority', 'status', 'due_date']`, blocking any unapproved attributes even if an unexpected payload bypassed request validation.

### Q5: How did you ensure database defaults, model defaults, form defaults, and validation rules remain consistent?
**Answer**: We centralized domain options as model constants (`Task::PRIORITIES` and `Task::STATUSES`). The migration sets database defaults (`Medium` and `Pending`), `TaskRequest` enforces rules with `Rule::in(Task::PRIORITIES)` and `Rule::in(Task::STATUSES)`, and the create action initializes a new `Task(['priority' => 'Medium', 'status' => 'Pending'])`. In `form.blade.php`, `old('field', $task->field)` consistently binds defaults across both create and edit views.
