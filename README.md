# RoleGuard CMS 登录注册、角色升级与封禁计划

## 1. 项目名称

项目名：**CMS**

项目描述：

一个基于 Laravel 13 的简易后台权限管理系统，使用手写 Session Auth + Blade，实现普通注册、账号登录、admin 升级申请、root 审核、角色降级、账号封禁、操作日志等后台核心流程。

英文描述：

A Laravel-based mini CMS backoffice system with custom session authentication, role-based access control, admin upgrade workflow, root approval, ban/unban logic, and account event logging.

---

## 2. Summary

基于当前纯 Laravel 13 骨架，第一版不引入 Breeze、Jetstream 等认证脚手架依赖，使用 Laravel 内置 Session Auth + Blade 手写登录、注册、退出和基础后台流程。

最终采用“账号升级申请”路线：

- 注册页不暴露邀请码。
- 所有新注册账号默认都是 `user`。
- 用户登录后，在账户管理页输入固定邀请码 `123456`。
- 输入正确邀请码后，只是提交 admin 升级申请。
- 申请期间账号仍然是 `user`。
- root 审核通过后，账号才从 `user` 变成 `admin`。
- root 不通过前端注册创建，只允许手动写入数据库或通过 tinker 创建。
- ban 不写进 `role`，ban 是账号登录状态，不是角色。
- 降级和 ban 分开：
  - 降级：改变 `role`
  - ban：改变账号可登录状态

---

## 3. 核心规则

### 3.1 角色规则

系统只有三种角色：

- `user`
- `admin`
- `root`

角色语义：

| 角色 | 来源 | 能力 |
|---|---|---|
| `user` | 普通注册 | 登录、进入 dashboard、提交 admin 升级申请 |
| `admin` | root 审核通过 user 的升级申请后获得 | 管理普通 user，可 ban/unban user |
| `root` | 手动创建 | 审核 admin 升级申请、管理 admin、降级 admin、ban/unban admin |

### 3.2 注册规则

注册页只有常规字段：

- `name`
- `email`
- `password`
- `password_confirmation`

注册时不处理邀请码。

注册成功后：

- 创建 `role = user`
- 自动登录
- 跳转 dashboard

### 3.3 登录规则

登录允许：

- `user`
- `admin`
- `root`

但被 ban 的账号不能登录。

登录流程：

1. 校验 email/password
2. 如果账号不存在或密码错误，返回登录错误
3. 如果 `banned_at` 不为空，拒绝登录
4. 登录成功后重新生成 session
5. 跳转 dashboard

### 3.4 Admin 升级申请规则

用户登录后进入账户管理页。

当用户满足以下条件时，显示 admin 升级申请表单：

- 当前角色是 `user`
- `admin_upgrade_requested_at` 为空
- 当前账号未被 ban

用户输入邀请码：

- 输入错误邀请码：返回表单错误，不改变状态
- 输入 `123456`：写入 `admin_upgrade_requested_at = now()`
- 写入一条 `request_admin` 日志
- 用户仍然保持 `role = user`
- 页面提示等待 root 审核

已提交申请的 user：

- 不再显示邀请码输入框
- 显示“已提交 admin 升级申请，等待 root 审核”

当前为 `admin` 或 `root`：

- 不显示升级申请入口

### 3.5 Root 审核升级规则

root 后台显示待审核列表。

待审核用户筛选条件：

- `role = user`
- `admin_upgrade_requested_at` 不为空
- `banned_at` 为空

root 点击通过后：

- `role` 从 `user` 改为 `admin`
- `admin_upgrade_requested_at` 清空
- `admin_promoted_at = now()`
- `admin_promoted_by = 当前 root id`
- 写入 `promote_to_admin` 日志

第一版不做“拒绝申请”流程。

如果 root 不处理，该申请保持 pending。

### 3.6 Admin 降级规则

root 可以把 admin 降级回 user。

降级时：

- `role` 从 `admin` 改为 `user`
- `admin_demoted_at = now()`
- `admin_demoted_by = 当前 root id`
- `admin_upgrade_requested_at` 清空
- 写入 `demote_to_user` 日志

限制：

- root 不能降级自己
- root 不能通过前端把自己变成 user
- admin 不能降级 admin
- user 不能执行降级操作

### 3.7 Ban / Unban 规则

ban 是账号状态，不是角色。

不要把 ban 写成：

- `role = banned`
- `role = disabled`
- `role = pending`

正确做法是使用：

- `banned_at`
- `banned_by`
- `ban_reason`

权限规则：

| 操作者 | 可操作对象 |
|---|---|
| admin | 只能 ban/unban 普通 user |
| root | 只能 ban/unban admin |
| user | 不能 ban/unban 任何人 |

ban 时：

- 写入 `banned_at = now()`
- 写入 `banned_by = 当前操作者 id`
- 写入 `ban_reason`
- 写入 `ban` 日志

unban 时：

- 清空 `banned_at`
- 清空 `banned_by`
- 清空 `ban_reason`
- 写入 `unban` 日志

被 ban 的账号：

- 不能登录
- 如果已经登录，访问受保护页面时应被拒绝或自动退出

---

## 4. 数据库设计

核心原则：

- `users` 表保存当前状态和最近关键时间。
- `user_account_events` 表保存历史过程。
- `role` 只表达角色身份。
- ban、升级申请、降级记录都不要塞进 `role`。

---

## 5. Tables

### 5.1 users 表

保留 Laravel 默认字段：

- `id`
- `name`
- `email`
- `email_verified_at`
- `password`
- `remember_token`
- `created_at`
- `updated_at`

新增字段：

```txt
role
admin_upgrade_requested_at
admin_promoted_at
admin_promoted_by
admin_demoted_at
admin_demoted_by
banned_at
banned_by
ban_reason

完整字段：
users
- id
- name
- email
- email_verified_at
- password
- remember_token
- role
- admin_upgrade_requested_at
- admin_promoted_at
- admin_promoted_by
- admin_demoted_at
- admin_demoted_by
- banned_at
- banned_by
- ban_reason
- created_at
- updated_at

role	string	当前角色，允许值：user、admin、root，默认 user
admin_upgrade_requested_at	nullable timestamp	用户提交 admin 升级申请的时间
admin_promoted_at	nullable timestamp	最近一次被升级为 admin 的时间
admin_promoted_by	nullable foreign id	最近一次升级该账号的 root id
admin_demoted_at	nullable timestamp	最近一次被降级为 user 的时间
admin_demoted_by	nullable foreign id	最近一次降级该账号的 root id
banned_at	nullable timestamp	被封禁时间，非空表示账号被 ban
banned_by	nullable foreign id	执行 ban 的操作者 id
ban_reason	nullable string	ban 原因
email_verified_at	nullable timestamp	保留字段，第一版不做邮箱验证

###5.2 user_account_events 表

用于记录用户账号相关操作历史。

结构：

user_account_events
- id
- target_user_id
- actor_user_id
- type
- old_role
- new_role
- metadata
- created_at

target_user_id	foreign id	被操作的用户
actor_user_id	nullable foreign id	操作者，系统行为可为空
type	string	事件类型
old_role	nullable string	操作前角色
new_role	nullable string	操作后角色
metadata	nullable json	扩展信息，比如 ban reason
created_at	timestamp	事件发生时间

事件类型第一版固定为：

request_admin
promote_to_admin
demote_to_user
ban
unban

示例：

user 输入邀请码申请 admin：
type = request_admin
old_role = user
new_role = user

root 通过 admin 申请：
type = promote_to_admin
old_role = user
new_role = admin

root 降级 admin：
type = demote_to_user
old_role = admin
new_role = user

admin ban user：
type = ban
old_role = user
new_role = user
metadata = {"reason": "xxx"}

admin unban user：
type = unban
old_role = user
new_role = user


##7. 路由计划
###7.1 公开路由
GET  /                      welcome
GET  /register              auth.register.create
POST /register              auth.register.store
GET  /login                 auth.login.create
POST /login                 auth.login.store

建议路由名：

home
register
login
###7.2 登录后路由

需要 auth 和 not_banned middleware。

GET  /dashboard             dashboard
POST /logout                logout

GET  /account               account.edit
POST /account/admin-request account.admin-request.store

###7.3 Root 路由

需要：

auth
not_banned
root

路由：

GET   /root/admin-requests          root.admin-requests.index
PATCH /root/admin-requests/{user}   root.admin-requests.approve

GET   /root/admins                  root.admins.index
PATCH /root/admins/{user}/demote    root.admins.demote
PATCH /root/admins/{user}/ban       root.admins.ban
PATCH /root/admins/{user}/unban     root.admins.unban

###7.4 Admin 路由

需要：

auth
not_banned
admin

路由：

GET   /admin/users              admin.users.index
PATCH /admin/users/{user}/ban   admin.users.ban
PATCH /admin/users/{user}/unban admin.users.unban