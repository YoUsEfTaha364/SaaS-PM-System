# 🚀 SaaS Project Management System (Laravel 12)

## 📌 About The Project

This is a SaaS-based Project Management System built using **Laravel 12**.

The system allows users to create workspaces and manage projects and tasks in a structured and scalable way. Each workspace acts as an isolated environment where teams can collaborate efficiently.

The goal of this project is to simulate real-world SaaS platforms and apply backend best practices using Laravel.

---

## 🧠 Core Features

### 🏢 Workspaces

* Create and manage multiple workspaces
* Invite members to collaborate
* Workspace-based data isolation

### 📁 Projects

* Each workspace contains multiple projects
* Organize work into logical units

### ✅ Tasks

* Create and manage tasks داخل المشاريع
* Assign tasks to multiple users
* Task status tracking
* Due dates support
* Task filtering system

### 💬 Task Interaction

* Comments on tasks
* Replies to comments
* File attachments
* Full task activity log (tracking all actions)

### 🔔 Notifications System

* Notifications for all task actions:

  * Assignments
  * Comments
  * Status updates
* Real-time user awareness of changes

### 👥 Team Management

* Invite users to workspace
* If user exists → receives notification
* If not registered → receives email invitation

### 📊 Dashboard

* Displays statistics based on user activity
* Overview of created tasks, projects, and actions

---

## 🛠️ Tech Stack

* Laravel 12
* PHP 8+
* MySQL
* Laravel Notifications
* Laravel Queues (for async processes like emails)
* Eloquent ORM
* RESTful APIs

---

## 🏗️ Architecture

* MVC architecture
* Modular structure (Workspaces → Projects → Tasks)
* Event-driven actions (notifications & logs)
* Clean separation of concerns

---

## ⚙️ Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/your-repo.git
```

2. Install dependencies:

```bash
composer install
```

3. Setup environment:

```bash
cp .env.example .env
```

4. Generate app key:

```bash
php artisan key:generate
```

5. Configure database in `.env`

6. Run migrations:

```bash
php artisan migrate
```

7. Run the server:

```bash
php artisan serve
```

---

## 🔐 Authentication & Authorization

* Secure authentication system
* Workspace-based access control
* Role-based permissions (if implemented)

---

## 📬 Notifications & Invitations

* In-app notifications for task updates
* Email invitations for new users
* Queue-based processing for better performance

---

## 📚 What I Learned

* Building a scalable SaaS architecture
* Handling relationships (Workspaces → Projects → Tasks)
* Designing notification systems
* Managing user collaboration features
* Writing clean and maintainable Laravel code

---

## 📄 License

MIT License
