# Antigravity Project Rules - PREVO

## 1. Git Workflow & Push Policy (STRICT)
- **NO AUTOMATIC PUSH**: NEVER run `git push` automatically. Always stop at `git commit`.
- **Commit Locally**: When a task, feature, or bugfix is completed and verified, stage and commit the changes locally using `git commit`.
- **Explicit User Request for Push**: Only execute `git push` if and when the user explicitly instructs it (e.g., "push", "push ke github", "silakan push").
- **Atomic Commits**: Make atomic commits per feature or fix, not bundled across unrelated features or entire stages.
- **Commit Message Convention**: All commit messages MUST be written in English following Conventional Commits (e.g., `feat(...)`, `fix(...)`, `refactor(...)`, `style(...)`).
- **Protected Folders**: Never stage, commit, or push files from `docs/` or `tests/` (kept locally / excluded via `.gitignore`).

## 2. Code Quality & Campus Rules
- Ensure all modified PHP files pass `php -l` syntax validation before committing.
- Maintain existing architecture: business logic in `services/`, prepared statements via PDO in `config/database.php`, security guards in `includes/session.php` and `includes/security.php`.
- Respect Polinema campus boundaries: COD locations are strictly restricted to Polinema campus grounds.
