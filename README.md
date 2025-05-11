# RaceManager – Database Admin Tool for Game Curses

This repository contains the final project for the course Databases at the University of Girona. The project focuses on backend logic and interaction with a relational database using SQL and PHP.
The code is commented in Catalan.

## Program Description

RaceManager is a lightweight web-based admin tool developed in PHP for managing races, participants, and fuel invoicing in a fictional online racing video game. Designed for backend administrative use, the system connects to an Oracle database to manage race data, calculate post-race fuel costs, and present organized invoice records per user and vehicle.

## ⚠️ Important Notice

**This program is currently non-functional** due to its dependency on a private Oracle database hosted by my university. Student accounts used to access this system are automatically deleted at the end of each academic year. As such, the program cannot currently connect to the database and cannot be run or tested outside that environment.

## Key Features

- **Invoice Generation** after races:
  - Calculates fuel cost based on race time and €/min rate (stored in DB)
  - Applies fixed service fee and IVA (tax) from a parameters table
  - Handles cases where vehicles abandon the race by using the longest recorded finish time
- **Invoice Summary View**:
  - Grouped by user and vehicle
  - Shows detailed line items and totals
- **Database Structure** includes:
  - Vehicles, races, users, fuel types, and invoice tables
- **Simple Web Interface** using PHP and HTML
- Implements database operations via Oracle SQL

## Development

The project was built using PHP for the server-side logic, Oracle SQL for database management, and basic HTML/CSS for the user interface.

## Author

Gemma Reina

## License

This project is intended for educational purposes only
