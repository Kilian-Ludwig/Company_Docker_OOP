DROP DATABASE if exists company;
CREATE DATABASE company;
use company;

-- ============================================
-- Database Migration: employee Management System
-- ============================================

-- Tabelle: department
CREATE TABLE IF NOT EXISTS department (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departmentName VARCHAR(100) NOT NULL,
    description TEXT NULL,
    managerId INT NULL,
    isActive TINYINT(1) NOT NULL DEFAULT 1,
    isHiring TINYINT(1) NOT NULL DEFAULT 0,
    createdAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
git init
git add .
git commit -m "Initial commit"

-- Tabelle: employee
CREATE TABLE IF NOT EXISTS employee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employeeFirstName VARCHAR(100) NOT NULL,
    employeeLastName VARCHAR(100) NOT NULL,
    employeeEmail VARCHAR(100) NOT NULL UNIQUE,
    employeePhone VARCHAR(50) NOT NULL UNIQUE ,
    departmentId INT NOT NULL,
    position VARCHAR(100) NOT NULL,
    salary DECIMAL(12,2) NULL,
    isActive TINYINT(1) NOT NULL DEFAULT 1,
    createdAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (departmentId) REFERENCES department(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabelle: project
CREATE TABLE IF NOT EXISTS project (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ProjectName VARCHAR(200) NOT NULL,
    description TEXT NULL,
    departmentId INT NOT NULL,
    startDate DATE NOT NULL,
    endDate DATE NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'active',
    budget DECIMAL(12,2) NULL,
    createdAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (departmentId) REFERENCES department(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabelle: skill
CREATE TABLE IF NOT EXISTS skill (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    description TEXT NULL,
    createdAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabelle: employeeproject (Many-to-Many Zwischentabelle)
CREATE TABLE IF NOT EXISTS employeeproject (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employeeId INT NOT NULL,
    projectId INT NOT NULL,
    role VARCHAR(100) NOT NULL,
    hoursAllocated INT NULL,
    assignedDate DATE NULL,
    createdAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employeeId) REFERENCES employee(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (projectId) REFERENCES project(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_employee_project (employeeId, projectId)
);


-- ============================================
-- Testdaten (Optional)
-- ============================================

-- department Testdaten
INSERT INTO department (departmentName, description, managerId, isActive, isHiring) VALUES
    ('IT', 'Information Technology department', NULL, 1, 1),
    ('HR', 'Human Resources department', NULL, 1, 0),
    ('Sales', 'Sales and Marketing department', NULL, 1, 1);

-- employee Testdaten
INSERT INTO employee ( employeeFirstName, employeeLastName, employeeEmail, employeePhone, departmentId, position, salary, isActive) VALUES
    ('Max', 'Mustermann', 'max.mustermann@example.com', '+49 123 456789', 1, 'Senior Developer', 65000.00, 1),
    ('Anna', 'Schmidt', 'anna.schmidt@example.com', '+49 123 456790', 1, 'Junior Developer', 45000.00, 1),
    ('Peter', 'Meyer', 'peter.meyer@example.com', '+49 123 456791', 2, 'HR Manager', 55000.00, 1);


-- Project Testdaten
INSERT INTO project (ProjectName, description, departmentId, startDate, endDate, status, budget) VALUES
    ('Website Redesign', 'Complete redesign of company website', 1, '2025-01-15', NULL, 'active', 50000.00),
    ('CRM Implementation', 'Implement new CRM system', 1, '2025-02-01', '2025-12-31', 'active', 120000.00),
    ('Recruitment Campaign', 'Hire 10 new developers', 2, '2025-03-01', NULL, 'on_hold', 15000.00);

-- Skill Testdaten
INSERT INTO skill (name, category, description) VALUES
    ('PHP', 'Programming', 'PHP programming language'),
    ('Docker', 'Tools', 'Container platform'),
    ('React', 'Programming', 'JavaScript library for UI'),
    ('MySQL', 'Database', 'Relational database management'),
    ('Git', 'Tools', 'Version control system');

-- EmployeeProject Testdaten
INSERT INTO employeeproject (employeeId, projectId, role, hoursAllocated, assignedDate) VALUES
    (1, 1, 'Lead Developer', 160, '2025-01-15'),
    (1, 2, 'Technical Architect', 80, '2025-02-01'),
    (2, 1, 'Developer', 160, '2025-01-20'),
    (2, 2, 'Developer', 120, '2025-02-15');

-- Manager zuweisen (nachdem employee existiert)
UPDATE department SET managerId = 3 WHERE id = 2;
UPDATE department SET managerId = 1 WHERE id = 1;
