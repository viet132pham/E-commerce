# Huong dan co ban ve Laravel (theo structure va phong van Junior++)

Tai lieu nay duoc viet theo JD PHP Junior++ va CV cua ban, tap trung vao nen tang Laravel + structure + cac chu de phong van hay gap.

## 1) Laravel la gi (tra loi phong van ngan gon)
- Laravel la framework PHP ma nguon mo theo kien truc MVC, giup xay dung ung dung web co cau truc ro rang, de bao tri va mo rong. ?cite?turn0view0?
- Laravel cung cap he sinh thai lon va nhieu tinh nang ho tro nhu routing, xac thuc, ORM, validation, migration, artisan CLI. ?cite?turn0view0?

## 2) Kien truc MVC trong Laravel
- Model: xu ly du lieu + business logic (Eloquent ORM).
- View: giao dien (Blade template).
- Controller: xu ly request va ket noi Model/View.
Laravel implement MVC rat ro rang va co san cac cong cu ho tro. ?cite?turn0view0?

## 3) Laravel project structure (can nho khi phong van)
- `app/` core business: Models, Controllers, Services, Jobs, Policies.
- `routes/` dinh nghia routes. Mac dinh co `web.php`, `api.php`, `console.php`, `channels.php`. ?cite?turn0view0?
- `database/` migrations, seeders, factories.
- `resources/` view (Blade), frontend assets.
- `config/` cau hinh app.
- `public/` web root.
- `storage/` logs, cache, files.

## 4) Routing trong Laravel
- `routes/web.php`: web route (session, CSRF).
- `routes/api.php`: API route (stateless).
- `routes/console.php`: command.
- `routes/channels.php`: broadcasting.
Nhac dung route theo dung file de giam phu thuoc va de bao tri. ?cite?turn0view0?

## 5) Migration, Seeder, Factory
- Migration giup quan ly schema database bang code, ho tro versioning va de dong bo moi truong. ?cite?turn0view0?
- Seeder dung de tao du lieu mau nhanh (db:seed). ?cite?turn0view0?
- Factory tao fake data, ho tro testing. ?cite?turn0view0?

## 6) Artisan CLI
- `php artisan make:model`, `make:controller`, `make:migration`, `make:seeder`.
- `php artisan route:list` de kiem tra routing.
Artisan la cong cu CLI quan trong nhat khi lam viec voi Laravel. ?cite?turn0view0?

## 7) Middleware
- Middleware la lop loc request/response (auth, log, validate, v.v.).
- Hay dung khi can kiem soat quyen truy cap hoac xu ly truoc khi vao controller. ?cite?turn0view0?

## 8) Dependency Injection, Service Container, Facade
- DI la ky thuat inject dependency thay vi hard-code, giup test va maintain de hon. ?cite?turn0view0?
- Service Container la noi quan ly va resolve dependency (IoC).
- Facade la "static proxy" de goi service trong container (VD: `Cache::get`, `DB::table`).

## 9) Repository pattern (phu hop JD)
- Dat mot lop Repository de tach truy van DB khoi Controller.
- Loi ich: de test, de thay doi DB logic, giam coupling.
- Co the ket hop voi Service layer de xu ly business phuc tap.

## 10) Eloquent ORM (goc can biet)
- Quan he: `hasMany`, `belongsTo`, `belongsToMany`.
- Mass assignment: `$fillable` vs `$guarded`.
- Eager loading: `with()` de giam N+1.

## 11) Validation & Request lifecycle
- Validation trong Controller hoac FormRequest.
- Neu fail, tra ve loi 422 (API) voi danh sach errors.

## 12) Bao mat co ban (hay hoi phong van)
- CSRF (cho web form).
- Hash password (bcrypt).
- Validate input, khong tin data tu client.
- Su dung prepared statement/Eloquent chong SQL injection.

## 13) Docker (trung voi JD)
- Dong goi app + DB thanh container.
- Can biet Dockerfile, docker-compose, env.
- Thuc te: chay Laravel + MySQL bang docker-compose.

## 14) Git workflow co ban
- Branching (feature/bugfix), PR/MR review.
- Commit ro rang, nho gon.

## 15) Cach tu tin tra loi theo JD
- "Toi da lam PHP + MySQL + Docker + Git o du an Magento."
- "Toi co nen tang OOP, hieu MVC, co the hoc nhanh Laravel."
- "Toi co kinh nghiem lam team, review code va optimize performance."

## 16) Checklist on nhanh truoc phong van (1-2 ngay)
- On lai MVC + route + middleware.
- On migration/seed/factory, artisan.
- Doc ve service container, DI, facade.
- Doc ve Eloquent relationships va eager loading.
- Lam thu 1 CRUD nho (Product) bang Laravel.

---
Tai lieu nay duoc viet de phu hop voi CV Magento + JD PHP Junior++. Neu can, minh co the chuyen thanh ban "Q&A" de on phong van nhanh (co cau hoi + dap an ngan gon).
