# Flux.
**Your Academic Memory, Automated.**

![Uploading image.png…]()



Flux is a full-stack, AI-powered academic vault built for modern students. It eliminates the friction of manual grade tracking by using Google's Gemini Vision AI to instantly read, extract, and visualize data from uploaded report cards. 

More than just a storage vault, Flux acts as an intelligent academic strategist—providing granular subject analytics, historical trajectory charts, and a personalized AI Mentor that generates actionable study plans based on your real-time performance data.

---

## ⚡ Core Features

* **Instant AI Extraction:** Upload any report card (Image or PDF). The Gemini 2.5 OCR engine instantly parses subjects, grades, and terms without manual data entry.
* **Dynamic Analytics Engine:** Visualize your academic evolution over time with interactive trend lines, subject distribution bar charts, and micro-analytics for specific assessments.
* **The Vault:** A secure, grid-based archive of your entire academic history, beautifully color-coded and sorted by term.
* **Flux AI Mentor:** An integrated chatbot and strategic planner that analyzes your specific weaknesses and recommends targeted study sessions and action items.
* **3-Layer Deep Dive:** Drill down from your overall average (Layer 1), to a specific term's report card (Layer 2), straight into individual subject mastery and sub-topic trajectories (Layer 3).

---

## 📸 Platform Glimpse

### The Overview Dashboard
![Overview Dashboard](docs/images/dashboard_placeholder.png)
*(Place a screenshot of the main dashboard with the master trend line chart)*

### The Vault & Report Details
![The Vault](docs/images/vault_placeholder.png)
*(Place a screenshot of the Vault grid or the split-screen Subject Breakdown view)*

### AI Mentor Integration
![AI Mentor](docs/images/mentor_placeholder.png)
*(Place a screenshot of the AI Mentor chat window and Action Plan sidebar)*

### Seamless Upload OCR
![Upload Modal](docs/images/upload_placeholder.png)
*(Place a screenshot of the Glassmorphism upload modal with the scanning animation)*

---

## 🛠️ Tech Stack

**Frontend:**
* React 18 (Vite)
* Recharts (Data Visualization)
* Lucide React (Iconography)
* GSAP (Landing Page Animations)
* Vanilla CSS (Custom Glassmorphism & SaaS UI framework)

**Backend & AI:**
* PHP 8+ (REST API Endpoints)
* MySQL (Relational Database)
* Google Gemini 2.5 Flash / Vision API (OCR Data Extraction & LLM Mentorship)

---

## 🚀 Local Setup & Installation

### 1. Database Configuration
1. Ensure you have XAMPP (or equivalent) installed and running (Apache & MySQL).
2. Create a new MySQL database named `flux_db`.
3. Import the required SQL tables (Users, Scans, Subject Scores) into your database.

### 2. Backend API Setup
1. Clone this repository into your local web server directory (e.g., `htdocs/flux`).
2. Update your database connection strings inside the PHP files located in `api/`.
3. Ensure your PHP server is successfully serving the files on `localhost`.

### 3. Frontend Setup
Navigate into the frontend directory and install dependencies:
```bash
cd flux-frontend
npm install
```
Start the Vite development server:
```
Bash
npm run dev
```
### 4. API Key Configuration
Upon your first local run, register a new account. The onboarding cutscenes will prompt you to enter a Google Gemini API Key. This key is securely saved to your local environment to power the upload extraction and AI Mentor features.

### 🤝 Roadmap
[x] UI/UX Design & Frontend Architecture

[x] Analytics Engine & Recharts Integration

[x] AI Mentor Interface

[ ] Backend OCR Processing Script (upload_report.php)

[ ] Direct Gemini API Integration for Chat

[ ] PDF Parsing Support

Designed and Engineered by Levi Gatimu Ngugi.
