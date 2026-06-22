# Laravel-CMS Current Feature Inventory

Last checked: 2026-06-20

This document records the current Laravel-CMS feature surface based on the live checkout in `D:\code\laravel\Laravel-CMS`.

## Runtime Notes

- PHP is provided by Laravel Herd at `C:\Users\wang\.config\herd\bin\php85\php.exe`.
- Route inspection command used:

```powershell
& 'C:\Users\wang\.config\herd\bin\php85\php.exe' artisan route:list --except-vendor
```

- The application currently reports 32 non-vendor routes.

## Current Features

### Public Site

- Home page at `/`.
- Public article list at `/published/articles`.
- Public article detail page at `/published/articles/{article}-{slug}`.
- Public article list only shows articles with `published` status.
- Public article list supports title search through the `q` query parameter.
- Public article detail rejects non-published articles with a 404.
- If a public article URL uses an outdated slug, it redirects to the canonical slug with a 301 response.

### Authentication

- User registration with name, email, and password.
- Registration sends a six-digit verification code by email.
- Pending registration data is stored temporarily in cache.
- Verification creates the user, marks the email as verified, logs the user in, and clears pending registration state.
- Users can resend the registration verification code.
- Login supports remember-me.
- Admin users are redirected to the admin dashboard after login.
- Regular users are redirected to the public article list after login.
- Logout invalidates the session and redirects to the home page.
- Forgot password sends a six-digit reset code by email.
- Password reset verifies the code stored in `password_reset_tokens`.

### Author Article Management

- Authenticated users can list their own articles.
- Authenticated users can create draft articles.
- Authenticated users can view their own articles.
- Authenticated users can edit articles only when the article is in an editable status.
- Editable statuses are `draft`, `withdrawn`, and `rejected`.
- Authors can submit editable articles for review.
- Authors can withdraw articles while they are pending review.

### Admin Review Workflow

- Admin routes are protected by `auth` and `admin` middleware.
- Admin users can view the admin dashboard.
- Admin users can view pending article reviews.
- Admin users can open an article review detail page.
- Admin users can approve and publish pending articles.
- Admin users can reject pending articles with a required reason.
- Admin users can take down published articles.
- Review decisions are recorded as `ArticleReviewAction` audit records.

### User Settings

- Authenticated users can view settings.
- Authenticated users can update their password after providing the current password.

## Controllers

### CMS Controllers

- `App\Http\Controllers\Cms\ArticleController`
  - Handles authenticated author article pages and actions.
  - Methods: `index`, `create`, `store`, `show`, `edit`, `update`, `submit`, `withdraw`.
  - Uses `StoreArticleRequest`, `UpdateArticleRequest`, `ArticleWorkflowService`, and article policies.

- `App\Http\Controllers\Cms\PublishedArticleController`
  - Handles public article listing and public article detail pages.
  - Methods: `index`, `show`.
  - Filters list results to `ArticleStatus::Published`.
  - Supports title search with the `q` query parameter.
  - Performs canonical slug redirects.

### Admin Controllers

- `App\Http\Controllers\Admin\AdminDashboardController`
  - Invokable controller for `/admin/dashboard`.

- `App\Http\Controllers\Admin\AdminArticleReviewController`
  - Handles admin review queue and review decisions.
  - Methods: `index`, `show`, `approve`, `reject`, `takeDown`.
  - Uses `ReviewRejectRequest`, `ReviewTakeDownRequest`, `ArticleWorkflowService`, and article policies.

### Auth Controllers

- `App\Http\Controllers\Auth\AuthenticatedSessionController`
  - Handles login form, login submission, and logout.

- `App\Http\Controllers\Auth\RegisteredUserController`
  - Handles registration, registration verification, and verification-code resend.
  - Sends `RegistrationVerificationCode` notifications.

- `App\Http\Controllers\Auth\PasswordResetCodeController`
  - Handles forgot-password and reset-password flows.
  - Sends `PasswordResetCode` notifications.

### User Controllers

- `App\Http\Controllers\User\DashboardController`
  - Invokable controller for `/dashboard`.

- `App\Http\Controllers\User\SettingsController`
  - Handles settings page and password update.

## Requests

### Article Requests

- `App\Http\Requests\StoreArticleRequest`
  - Authorizes `create` on `Article`.
  - Validates `title`, `summary`, and `content`.

- `App\Http\Requests\UpdateArticleRequest`
  - Authorizes `update` on the route article.
  - Validates `title`, `summary`, and `content`.

### Review Requests

- `App\Http\Requests\ReviewRejectRequest`
  - Authorizes `reject` on the route article.
  - Requires `reason`.

- `App\Http\Requests\ReviewTakeDownRequest`
  - Authorizes `takeDown` on the route article.
  - Accepts an optional `reason`.

### Auth Requests

- `App\Http\Requests\Auth\LoginRequest`
  - Validates email and password.

- `App\Http\Requests\Auth\RegisterRequest`
  - Validates name, unique email, and confirmed password.

- `App\Http\Requests\Auth\VerifyRegistrationRequest`
  - Validates email and six-digit verification code.

- `App\Http\Requests\Auth\ForgotPasswordRequest`
  - Validates reset email.

- `App\Http\Requests\Auth\ResetPasswordRequest`
  - Validates email, six-digit reset code, and confirmed password.

### Settings Requests

- `App\Http\Requests\UpdatePasswordRequest`
  - Requires current password.
  - Requires confirmed new password with minimum length 6.

## Services, Jobs, Middleware, Policies, Notifications

### Services

- `App\Services\ArticleWorkflowService`
  - Owns article workflow transitions.
  - Handles submit, withdraw, approve, reject, and take-down.
  - Uses database transactions and row locking for review decisions.
  - Writes `ArticleReviewAction` records for admin review actions.

### Jobs

- No application-level `app/Jobs` directory or custom job class currently exists.
- The project has Laravel's default jobs table migration, but current business logic does not define custom queue jobs.

### Middleware

- `App\Http\Middleware\IsAdmin`
  - Aborts with 403 unless the authenticated user is an admin.
  - Registered as the `admin` middleware alias in `bootstrap/app.php`.

### Policies

- `App\Policies\ArticlePolicy`
  - Authors can view their own articles.
  - Admins can view articles.
  - Authors can update and submit only editable articles.
  - Authors can withdraw pending-review articles.
  - Admins can approve, reject, and take down articles.

### Notifications

- `App\Notifications\Auth\RegistrationVerificationCode`
  - Sends registration verification codes through mail.

- `App\Notifications\Auth\PasswordResetCode`
  - Sends password reset codes through mail.

## Routes

Routes are loaded from `routes/web.php`, which requires the auth, admin, and article route files.

### `routes/web.php`

- `GET /` -> `home`

### `routes/auth.php`

- `GET /register` -> `register`
- `POST /register`
- `GET /register/verify` -> `register.verify`
- `POST /register/verify` -> `register.confirm`
- `POST /register/verify/resend` -> `register.verify.resend`
- `GET /login` -> `login`
- `POST /login`
- `POST /logout` -> `logout`
- `GET /forgot-password` -> `password.request`
- `POST /forgot-password` -> `password.email`
- `GET /reset-password` -> `password.reset`
- `POST /reset-password` -> `password.update`
- `GET /dashboard` -> `dashboard`
- `GET /settings` -> `settings.edit`
- `PUT /settings/password` -> `settings.password.update`

### `routes/article.php`

- `GET /published/articles` -> `published.articles.index`
- `GET /published/articles/{article}-{slug}` -> `published.articles.show`
- `GET /articles` -> `articles.index`
- `GET /articles/create` -> `articles.create`
- `POST /articles` -> `articles.store`
- `GET /articles/{article}` -> `articles.show`
- `GET /articles/{article}/edit` -> `articles.edit`
- `PATCH /articles/{article}` -> `articles.update`
- `POST /articles/{article}/submit` -> `articles.submit`
- `POST /articles/{article}/withdraw` -> `articles.withdraw`

### `routes/admin.php`

- `GET /admin/dashboard` -> `admin.dashboard`
- `GET /admin/articles/reviews` -> `admin.articles.reviews.index`
- `GET /admin/articles/{article}` -> `admin.articles.show`
- `POST /admin/articles/{article}/approve` -> `admin.articles.approve`
- `POST /admin/articles/{article}/reject` -> `admin.articles.reject`
- `POST /admin/articles/{article}/take-down` -> `admin.articles.take-down`

## Views

### Public Views

- `resources/views/welcome.blade.php`
- `resources/views/published/articles/index.blade.php`
- `resources/views/published/articles/show.blade.php`

### Auth Views

- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/verify-registration.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`

### Author Article Views

- `resources/views/articles/index.blade.php`
- `resources/views/articles/create.blade.php`
- `resources/views/articles/edit.blade.php`
- `resources/views/articles/show.blade.php`
- `resources/views/articles/partials/form.blade.php`

### Admin Views

- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/articles/reviews/index.blade.php`
- `resources/views/admin/articles/show.blade.php`

### User Views

- `resources/views/dashboard.blade.php`
- `resources/views/settings/edit.blade.php`

### Layouts and Components

- `resources/views/layouts/app.blade.php`
- `resources/views/components/app-layout.blade.php`
- `resources/views/components/layouts/guest.blade.php`
- `resources/views/components/ui/button.blade.php`
- `resources/views/components/ui/card.blade.php`
- `resources/views/components/ui/input.blade.php`
- `resources/views/components/ui/label.blade.php`
- `resources/views/components/ui/select.blade.php`

## Models

### `App\Models\User`

- Fillable fields: `name`, `email`, `password`, `role`.
- Hidden fields: `password`, `remember_token`.
- Casts:
  - `email_verified_at` to datetime.
  - `password` to hashed.
  - `role` to `UserRole`.
- Relationships:
  - `articles()` has many articles through `author_id`.
- Helpers:
  - `isUser()`
  - `isAdmin()`

### `App\Models\Article`

- Fillable fields:
  - `author_id`
  - `title`
  - `summary`
  - `content`
  - `status`
  - `approved_by`
  - `approved_at`
  - `rejected_by`
  - `rejected_at`
  - `reject_reason`
  - `withdrawn_at`
  - `taken_down_at`
- Casts:
  - `status` to `ArticleStatus`.
  - review timestamps to datetime.
- Relationships:
  - `author()`
  - `approver()`
  - `rejecter()`
  - `reviewActions()`
- Computed attributes and helpers:
  - `slug` is derived from title with `Str::slug`; it is not persisted.
  - `publicRouteParameters()` returns route parameters for public article URLs.

### `App\Models\ArticleReviewAction`

- Fillable fields:
  - `article_id`
  - `admin_id`
  - `action`
  - `from_status`
  - `to_status`
  - `reason`
- Casts:
  - `action` to `ArticleReviewActionType`.
  - `from_status` and `to_status` to `ArticleStatus`.
- Relationships:
  - `article()`
  - `admin()`

## Enums

### `App\Enums\UserRole`

- `User`
- `Admin`

### `App\Enums\ArticleStatus`

- `Draft`
- `PendingReview`
- `Withdrawn`
- `Rejected`
- `Published`
- `TakenDown`

### `App\Enums\ArticleReviewActionType`

- `Approve`
- `Reject`
- `TakeDown`

## Database and Seeders

### Main Tables

- `users`
- `password_reset_tokens`
- `sessions`
- `articles`
- `article_review_actions`
- Laravel default cache and jobs tables

### Article Schema Notes

- `articles.slug` was removed by `2026_05_29_132457_remove_slug_from_articles_table.php`.
- Article slug is now computed from the title in the `Article` model.
- Article workflow fields include approval, rejection, withdrawal, and take-down timestamps.

### Review Action Schema Notes

- `article_review_actions` records admin review transitions.
- The earlier `is_open` and `open_slot` fields were removed by `2026_05_28_000009_remove_open_slot_from_article_review_actions_table.php`.

### Seeders

- `DatabaseSeeder` calls `UserSeeder` and `ArticleSeeder`.
- `UserSeeder` creates or updates:
  - `000001@admin.com`
  - `000001@user.com`
  - `000002@user.com`
- `ArticleSeeder` creates or updates 20 demo articles with mixed statuses.

## Tests Present

- `tests/Feature/ArticleWorkflowTest.php`
- `tests/Feature/DashboardTest.php`
- `tests/Feature/DemoSeederTest.php`
- `tests/Feature/SettingsTest.php`
- `tests/Feature/Auth/AuthenticationTest.php`
- `tests/Feature/Auth/PasswordResetTest.php`
- `tests/Feature/Auth/RegistrationTest.php`
- Default example tests are also present.
