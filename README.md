**Project Overview:**
Repository ini digunakan untuk melakukan otomatisasi CI/CD deployment pada sebuah cloud server dengan Arsitektur App Layer, Data Layer, dan Edge Layer.

**Diagram Arsitektur:**
<br><img width="492" height="793" alt="diagram" src="https://github.com/user-attachments/assets/fd352cf0-da74-4be4-b1af-c63e5050c2b4" />

**Tech Stack:**
- **Docker** → Containerization
- **PHP** → Web Application
- **Python** → Function as a Service (FaaS)
- **MySQL** → Database
- **MiniO** → Object Storage
- **CAdvisor, Prometheus, Grafana** → Cloud Resource Monitoring
- **Uptime Kuma** → Web Monitoring
- **Nginx** → Web Server
- **Nginx Proxy Manager** → Reverse Proxy & Load Balancing

**CI/CD Workflow:**
Repository Github ini terhubung dengan Github Actions dan Ubuntu Server untuk melakukan CI/CD _auto deployment_ pada sebuah server cloud.
Ketika ada pembaharuan fitur web, ketika commit maka akan secara otomatis melakukan _auto deployment_ pada server.
