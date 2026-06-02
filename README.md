<div align="center">
  
  <img src="https://readme-typing-svg.demolab.com?font=Fira+Code&weight=600&size=28&duration=3000&pause=500&color=38BDF8&center=true&vCenter=true&width=500&lines=Hello%2C+I'm+UKOBUKEYE+Mordekai;Full-Stack+Developer;Software+Engineer;Tech+Enthusiast" alt="Typing SVG" />
  
  <img src="https://img.shields.io/badge/Rwanda-00A3E0?style=for-the-badge&logo=data:image/svg%2bxml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA5MDAgNjAwIj48cmVjdCB3aWR0aD0iOTAwIiBoZWlnaHQ9IjYwMCIgZmlsbD0iIzIwQzNCQiIvPjxjaXJjbGUgY3g9IjQ1MCIgY3k9IjMwMCIgcj0iMTUwIiBmaWxsPSIjRkZEOjAwIi8+PHJlY3Qgd2lkdGg9IjkwMCIgaGVpZ2h0PSIyMDAiIGZpbGw9IiMwMEExREUiLz48cmVjdCB5PSI0MDAiIHdpZHRoPSI5MDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRkZEOjAwIi8+PC9zdmc+" alt="Rwanda" />
  
  <img src="https://komarev.com/ghpvc/?username=Mordekai32&label=Profile%20Views&color=0e75b6&style=flat" alt="Profile Views" />
  
</div>

---

## 👨‍💻 Who Am I?

> *"Building robust digital solutions that transform African businesses, one line of code at a time."*

I'm a professional **Software Developer** passionate about crafting high-performance web applications that solve real-world business problems. With deep expertise in the **MERN Stack** and **TypeScript**, I bridge the gap between complex technical requirements and user-friendly experiences.

### 🎯 Current Focus

- ⚡ Building scalable inventory systems (like **SIMS PRO V6.0**)
- 📚 Mastering **Next.js** and **Server Components**
- 🌍 Contributing to Rwanda's growing tech ecosystem
- 🤝 Open for **freelance** and **collaboration** opportunities

---

## 🛠️ Technical Arsenal

<div align="center">

### Frontend Development
| Technology | Proficiency | Projects |
|------------|-------------|----------|
| <img src="https://img.shields.io/badge/React-20232A?style=flat&logo=react&logoColor=61DAFB" alt="React"> | ⭐⭐⭐⭐⭐ | 15+ |
| <img src="https://img.shields.io/badge/TypeScript-007ACC?style=flat&logo=typescript&logoColor=white" alt="TypeScript"> | ⭐⭐⭐⭐⭐ | 10+ |
| <img src="https://img.shields.io/badge/Next.js-000000?style=flat&logo=next.js&logoColor=white" alt="Next.js"> | ⭐⭐⭐⭐ | 5+ |
| <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat&logo=tailwind-css&logoColor=white" alt="Tailwind"> | ⭐⭐⭐⭐⭐ | 12+ |

### Backend Development
| Technology | Experience | APIs Built |
|------------|------------|------------|
| <img src="https://img.shields.io/badge/Node.js-339933?style=flat&logo=nodedotjs&logoColor=white" alt="Node.js"> | 4+ years | 25+ |
| <img src="https://img.shields.io/badge/Express.js-000000?style=flat&logo=express&logoColor=white" alt="Express"> | 4+ years | 25+ |
| <img src="https://img.shields.io/badge/REST_API-FF6C37?style=flat&logo=postman&logoColor=white" alt="REST API"> | 4+ years | 30+ |

### Databases
| Database | Use Case | Performance |
|----------|----------|-------------|
| <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=flat&logo=postgresql&logoColor=white" alt="PostgreSQL"> | Complex queries | Optimized |
| <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL"> | Inventory systems | ⚡ Fast |
| <img src="https://img.shields.io/badge/MongoDB-47A248?style=flat&logo=mongodb&logoColor=white" alt="MongoDB"> | Flexible schemas | Scalable |

### DevOps & Tools
- <img src="https://img.shields.io/badge/Git-F05032?style=flat&logo=git&logoColor=white" alt="Git"> Version Control
- <img src="https://img.shields.io/badge/GitHub-181717?style=flat&logo=github&logoColor=white" alt="GitHub"> Collaboration
- <img src="https://img.shields.io/badge/Postman-FF6C37?style=flat&logo=postman&logoColor=white" alt="Postman"> API Testing
- <img src="https://img.shields.io/badge/Vercel-000000?style=flat&logo=vercel&logoColor=white" alt="Vercel"> Deployment
- <img src="https://img.shields.io/badge/XLSX-217346?style=flat&logo=microsoftexcel&logoColor=white" alt="Excel"> Data Engine

</div>

---

## 🏆 Featured Project: SIMS PRO V6.0

<div align="center">
  
  <img src="https://img.shields.io/badge/Version-6.0-blue?style=for-the-badge" alt="V6.0">
  <img src="https://img.shields.io/badge/Status-Production_Success-green?style=for-the-badge" alt="Production">
  <img src="https://img.shields.io/badge/Type-Open_Source-orange?style=for-the-badge" alt="Open Source">
  
</div>

### 📦 Spare Parts Inventory Management System

| Aspect | Detail |
|--------|--------|
| **Problem Solved** | Manual inventory tracking causing stock discrepancies and lost revenue |
| **Solution** | Real-time dashboard with automated stock rebalancing and Excel exports |
| **Business Impact** | Reduced inventory errors by **95%** and saved **10+ hours/week** in reconciliation |
| **Key Innovation** | Complex SQL transaction rollback ensuring data integrity |

<details>
<summary><strong>🔧 Technical Deep Dive</strong></summary>

```sql
-- Automated stock rebalancing logic
CREATE TRIGGER rebalance_stock 
AFTER DELETE ON transactions
BEGIN
    UPDATE inventory 
    SET quantity = quantity + OLD.quantity 
    WHERE part_id = OLD.part_id 
    AND transaction_type = 'STOCK_OUT';
END;
