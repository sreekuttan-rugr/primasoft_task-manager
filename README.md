# 📋 Laravel Task Management System

A comprehensive task management application built with Laravel featuring bulk import capabilities, intelligent priority scoring, role-based access control, and real-time notifications.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange?style=flat-square&logo=mysql)
![Redis](https://img.shields.io/badge/Redis-Optional-red?style=flat-square&logo=redis)

## 🌟 Features

- **📊 Bulk Task Import** - CSV-based task creation with validation and error reporting
- **🎯 Intelligent Priority Scoring** - Dynamic priority calculation using urgency, impact, and effort metrics
- **👥 Role-Based Access Control** - Admin, Manager, and User roles with granular permissions
- **📬 Queue-Based Notifications** - Email notifications for task assignments and updates
- **📈 Admin Dashboard** - Real-time statistics and analytics
- **🔌 RESTful API** - Complete API endpoints for external integrations
- **⚡ Real-time Updates** - Queue-powered background processing
- **🔍 Advanced Filtering** - Search and filter tasks by multiple criteria

## 🚀 Requirements

| Component | Version |
|-----------|---------|
| PHP | >= 8.1 |
| Laravel | >= 10.x |
| Composer | Latest |
| Database | MySQL 8.0+ / PostgreSQL 13+ |
| Redis | Optional (recommended for queues) |
| Node.js | >= 16.x |
| npm/yarn | Latest |

## ⚙️ Installation & Setup

### 1. Clone Repository
```bash
git clone https://github.com/sreekuttan-rugr/primasoft_task-manager.git
cd primasoft_task-manager
```

### 2. Install Dependencies
```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install && npm run build
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit your `.env` file:

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=your_password

# Mail Configuration (for notifications)
MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@taskmanager.com"
MAIL_FROM_NAME="Task Manager"

# Queue Configuration
QUEUE_CONNECTION=database
# For Redis: QUEUE_CONNECTION=redis

# Redis Configuration (if using Redis)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 5. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```


### 5.1. User deatils
admin_mail: sreekuttan@admin.com
'password' => password,

user_email => 'user@test.com',
password => password


### 6. Start Services
```bash
# Start Laravel development server
php artisan serve

# Start queue worker (in separate terminal)
php artisan queue:work

# For development - watch for file changes
npm run dev
```

## 👥 User Roles & Permissions

### Admin
- ✅ View and manage all tasks
- ✅ Access admin dashboard
- ✅ Bulk import tasks via CSV
- ✅ System configuration access

### Manager (need to implement)
- ✅ Assign tasks to team members
- ✅ View team tasks and progress
- ✅ Create and edit tasks
- ✅ Access team statistics
- ❌ Cannot manage users or system settings

### User
- ✅ View assigned tasks
- ✅ Update task status and progress
- ✅ Add comments to tasks
- ❌ Cannot assign tasks to others
- ❌ Limited dashboard access

## 🎯 Priority Scoring Algorithm

Tasks are automatically scored using a sophisticated algorithm:

```
Priority Score = √(Urgency × Impact / Effort)
```

### Scoring Criteria

| Factor | Range | Description |
|--------|--------|-------------|
| **Urgency** | 1-10 | How time-sensitive is this task? |
| **Impact** | 1-10 | What's the business/project impact? |
| **Effort** | 1-10 | How much work is required? |

### Score Interpretation
- **8-10**: 🔴 Critical Priority
- **6-7.9**: 🟠 High Priority  
- **4-5.9**: 🟡 Medium Priority
- **1-3.9**: 🟢 Low Priority

## 📊 Bulk CSV Import

### CSV Format Requirements

Your CSV file must include these headers:

```csv
title,description,urgency,impact,effort,due_date,assigned_to,category_id
"Fix login bug","Critical security issue",9,8,3,"2024-12-31",1,2
"Update documentation","Improve user guides",4,6,5,"2024-12-15",2,1
```

### Field Specifications

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `title` | String | ✅ | Max 255 characters |
| `description` | Text | ❌ | Max 65,535 characters |
| `urgency` | Integer | ✅ | Range: 1-10 |
| `impact` | Integer | ✅ | Range: 1-10 |
| `effort` | Integer | ✅ | Range: 1-10 |
| `due_date` | Date | ✅ | Format: YYYY-MM-DD |
| `assigned_to` | Integer | ✅ | Valid user ID |
| `category_id` | Integer | ❌ | Valid category ID |

### Import Process
1. **Upload**: Admin uploads CSV via dashboard
2. **Validation**: Each row is validated against business rules
3. **Queue Processing**: Large imports are processed in background
4. **Notification**: Email report sent upon completion
5. **Error Handling**: Invalid rows are logged with specific errors

## 🔌 API Documentation

Base URL: `http://your-domain.com/api`

### Authentication
```bash
# Get API token
POST /api/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

### Task Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/api/tasks` | List all tasks | ✅ |
| `POST` | `/api/tasks` | Create new task | ✅ |
| `GET` | `/api/tasks/{id}` | Get task details | ✅ |
| `PUT` | `/api/tasks/{id}` | Update task | ✅ |
| `DELETE` | `/api/tasks/{id}` | Delete task | ✅ |


### Request Examples

#### Create Task
```bash
POST /api/tasks
Authorization: Bearer your-api-token
Content-Type: application/json

{
    "title": "Implement new feature",
    "description": "Add user profile functionality",
    "urgency": 7,
    "impact": 8,
    "effort": 6,
    "due_date": "2024-12-31",
    "assigned_to": 2,
    "category_id": 1
}
```

#### List Tasks with Filters
```bash
GET /api/tasks?status=pending&priority=high&assigned_to=2&page=1
Authorization: Bearer your-api-token
```

### Response Format
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Task title",
        "priority_score": 7.48,
        "status": "pending",
        "created_at": "2024-01-15T10:30:00.000000Z"
    },
    "message": "Task created successfully"
}
```

## 📈 Dashboard Analytics

### Available Metrics

#### Admin Dashboard (`/api/admin/dashboard`)
```json
{
    "summary": {
        "total_tasks": 1250,
        "completed_tasks": 456,
        "pending_tasks": 794,
        "overdue_tasks": 23
    },
    "priority_breakdown": {
        "critical": 45,
        "high": 123,
        "medium": 567,
        "low": 515
    },
    "team_performance": [
        {
            "user": "John Doe",
            "completed": 23,
            "pending": 7,
            "completion_rate": 76.7
        }
    ],
    "high_priority_tasks": [...]
}
```

## 📬 Notification System

### Email Notifications

#### Task Assignment
- **Trigger**: New task assigned to user
- **Recipients**: Assigned user, task creator
- **Content**: Task details, due date, priority

#### Task Updates
- **Trigger**: Task status/priority changes
- **Recipients**: Assigned user, stakeholders
- **Content**: Change summary, updated details

#### Bulk Import Completion
- **Trigger**: CSV import process completes
- **Recipients**: Admin who initiated import
- **Content**: Success/failure statistics, error report

### Queue Management

#### Start Queue Worker
```bash
# Development
php artisan queue:work

# Production (with Supervisor)
php artisan queue:work --daemon --tries=3 --timeout=60
```

#### Monitor Queue
```bash
# View failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry {job-id}

# Clear failed jobs
php artisan queue:flush
```

## 🧪 Testing

### Run Test Suite
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

### Test Categories
- **Unit Tests**: Models, Services, Repositories
- **Feature Tests**: Controllers, API endpoints
- **Integration Tests**: Complete workflows (import, notifications)

## 🔧 Maintenance Commands

### Daily Maintenance
```bash
# Clear expired tasks
php artisan tasks:cleanup

# Generate daily reports
php artisan reports:daily

# Optimize application
php artisan optimize
```

### Database Maintenance
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Reset failed jobs
php artisan queue:flush

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🚀 Deployment

### Production Setup

1. **Server Requirements**
   - PHP 8.1+ with required extensions
   - Web server (Apache/Nginx)
   - MySQL/PostgreSQL database
   - Redis server (recommended)
   - Supervisor for queue management

2. **Environment Configuration**
   ```bash
   # Set production environment
   APP_ENV=production
   APP_DEBUG=false
   
   # Configure queue worker
   QUEUE_CONNECTION=redis
   
   # Set up proper mail driver
   MAIL_MAILER=ses  # or mailgun, etc.
   ```

3. **Deploy Commands**
   ```bash
   # Install dependencies
   composer install --no-dev --optimize-autoloader
   
   # Cache configuration
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   
   # Run migrations
   php artisan migrate --force
   ```

### Queue Management with Supervisor

Create `/etc/supervisor/conf.d/laravel-worker.conf`:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/app/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/worker.log
stopwaitsecs=3600
```

## 🐛 Troubleshooting

### Common Issues

#### Queue Jobs Not Processing
```bash
# Check queue worker status
php artisan queue:work --verbose

# Restart queue workers
php artisan queue:restart
```

#### CSV Import Failing
- Verify CSV format matches requirements
- Check file encoding (UTF-8 recommended)
- Ensure all required fields are present
- Validate user IDs and category IDs exist

#### Email Notifications Not Sending
- Verify mail configuration in `.env`
- Test mail settings: `php artisan tinker` → `Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });`
- Check queue worker is running

#### Performance Issues
```bash
# Enable query logging for debugging
DB::enableQueryLog();
// Your code here
dd(DB::getQueryLog());

# Optimize database
php artisan db:show --counts
```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Queues](https://laravel.com/docs/queues)
- [Laravel Notifications](https://laravel.com/docs/notifications)
- [API Testing with Postman](https://www.postman.com/)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel Framework Team
- Contributors and testers
- Open source community

---

**Made with ❤ by [Sreekuttan](https://github.com/sreekuttan-rugr)**

For support or questions, please open an issue on GitHub.
