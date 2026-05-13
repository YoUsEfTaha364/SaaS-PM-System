# Laravel View Data Consistency Audit

Scan the entire Laravel project and analyze all Controllers and Blade views to detect mismatches between data passed from controllers and variables used in views.

## Goals

1. Identify all controller except Api controllers  methods that return views.
2. Extract all variables passed to each view via:
   - `view('...', [...])`
   - `view('...', compact(...))`
   - `return view(...)->with(...)`

3. For each corresponding Blade view:
   - Scan for all variables used (e.g., `$workspace`, `$project`, `$user`)
   - Detect variables that are used but NOT passed from the controller

4. Detect potential issues:
   - Undefined variables in Blade views
   - Variables passed but never used
   - Inconsistent naming (e.g., `$projects` vs `$project`)
   - Nested relationships that may be null (e.g., `$project->workspace->name`)

5. Suggest fixes:
   - Add missing variables in controller
   - Or refactor Blade to use existing variables (e.g., `$project->workspace` instead of `$workspace`)
   - Recommend best practices (avoid duplicate variables)

## Output format

For each issue:

- Controller: [ControllerName@method]
- View: [view name]
- Problem:
  - Variable `$workspace` is used but not passed
- Suggested Fix:
  - Pass `$workspace` from controller
  OR
  - Replace with `$project->workspace` in Blade

## Extra checks (optional but recommended)

- Ensure route-model binding matches variable names
- Ensure relationships exist in models
- Detect possible null access (e.g., `$project->workspace` when workspace may be null)

## Goal

Ensure every Blade view only uses variables that are explicitly passed or safely accessible through relationships.