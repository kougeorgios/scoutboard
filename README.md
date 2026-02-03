# scoutboard
Web application for Football's data and Analytics for Greek Super League 2025-2026

ScoutBoard is a web-based football analytics platform developed as part of a diploma thesis.  
The application focuses on the collection, processing, analysis, and visualization of football data, offering interactive statistics and insights for teams, competitions, players and coaches.

## 📌 Project Overview

ScoutBoard provides:
- League standings
- Home & away performance analysis
- Monthly statistics
- Team momentum graphs
- Goals for / goals against visualizations
- Players Profile
- Competitions Profile
- Clubs Profile
- Coaches Profie

The platform is designed to demonstrate how football data can be transformed into meaningful information through database processing and modern web technologies.

## 🛠 Technologies Used

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Charts & Visualization:** Chart.js
- **Data Source:** External football data API, SofaScore API. https://rapidapi.com/apidojo/api/sofascore
- **Version Control:** Git & GitHub

For team and player profiles, data is fetched in real-time via the API to ensure immediate access to the most current information. In contrast, the analysis of the Greek League utilizes a local relational database (MySQL), where teams and match results are stored.

SQL queries are used extensively to:
- Aggregate match results
- Calculate statistics
- Generate standings and analytics views


## 🚀 Installation & Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/kougeorgios/scoutboard.git

2. Import the database schema into MySQL.
   - Open **phpMyAdmin**.
   - Create a new database named `analytics`.
   - Import the `schema.sql` file located in the root directory. 

3.Configure database connection:
  Update database credentials in the PHP configuration files.

!! Must update the db_connect.php and config.php. !!

Run the project locally:

4. Use a local server environment (e.g. XAMPP, MAMP).
  Place the project folder inside the htdocs directory.

5. Access the application via browser:
  http://localhost/scoutboard
