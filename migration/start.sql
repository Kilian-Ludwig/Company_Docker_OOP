DROP DATABASE if exists company;
CREATE DATABASE company;
use company;

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


CREATE TABLE IF NOT EXISTS employee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employeeFirstName VARCHAR(100) NOT NULL,
    employeeLastName VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    employeePhone VARCHAR(50) NOT NULL UNIQUE ,
    departmentId INT NOT NULL,
    position VARCHAR(100) NOT NULL,
    salary DECIMAL(12,2) NULL,
    isActive TINYINT(1) NOT NULL DEFAULT 1,
    createdAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

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

CREATE TABLE IF NOT EXISTS skill (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    description TEXT NULL,
    createdAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

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
CREATE TABLE IF NOT EXISTS `users` (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL unique,
    passwordHash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    isActive TINYINT(1) NOT NULL DEFAULT 1,
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO department (departmentName, description, managerId, isActive, isHiring) VALUES
    ('Sales', 'Sales and Marketing department', NULL, 1, 1),
    ('Finance', 'Finance and Controlling department', NULL, 1, 0),
    ('HR', 'Human Resources department', NULL, 1, 0),
    ('IT', 'Information Technology department', NULL, 1, 1),
    ('Operations', 'Daily operations and logistics', NULL, 1, 0),
    ('Support', 'Customer support and helpdesk', NULL, 1, 1),
    ('R&D', 'Research and Development', NULL, 1, 1),
    ('QA', 'Quality Assurance department', NULL, 1, 0),
    ('Legal', 'Legal and compliance team', NULL, 1, 0),
    ('Admin', 'Office administration', NULL, 1, 0),
    ('Security', 'IT and physical security', NULL, 1, 0),
    ('Infrastructure', 'Servers and network team', NULL, 1, 1),
    ('Marketing', 'Brand and communication', NULL, 1, 1),
    ('Product', 'Product management', NULL, 1, 1),
    ('Data', 'Data analytics and BI', NULL, 1, 1),
    ('Training', 'Internal training and academy', NULL, 1, 0),
    ('Procurement', 'Purchasing and suppliers', NULL, 1, 0),
    ('Logistics', 'Warehouse and shipping', NULL, 1, 0),
    ('Management', 'Executive management board', NULL, 1, 0),
    ('Interns', 'Internship and trainees', NULL, 1, 1);

INSERT INTO employee (employeeFirstName, employeeLastName, email, employeePhone, departmentId, position, salary, isActive) VALUES
    ('Max', 'Mustermann', 'max.mustermann@example.com', '+49 123 456789', 1, 'Senior Developer', 65000.00, 1),
    ('Anna', 'Schmidt', 'anna.schmidt@example.com', '+49 123 456790', 1, 'Junior Developer', 45000.00, 1),
    ('Peter', 'Meyer', 'peter.meyer@example.com', '+49 123 456791', 2, 'HR Manager', 55000.00, 1),
    ('Julia', 'Weber', 'julia.weber@example.com', '+49 123 456792', 3, 'Sales Manager', 60000.00, 1),
    ('Lukas', 'Fischer', 'lukas.fischer@example.com', '+49 123 456793', 4, 'Accountant', 52000.00, 1),
    ('Sophie', 'Wagner', 'sophie.wagner@example.com', '+49 123 456794', 5, 'Operations Lead', 58000.00, 1),
    ('Tim', 'Becker', 'tim.becker@example.com', '+49 123 456795', 6, 'Support Agent', 38000.00, 1),
    ('Laura', 'Hoffmann', 'laura.hoffmann@example.com', '+49 123 456796', 7, 'R&D Engineer', 70000.00, 1),
    ('Jonas', 'Keller', 'jonas.keller@example.com', '+49 123 456797', 8, 'QA Engineer', 50000.00, 1),
    ('Marie', 'Schulz', 'marie.schulz@example.com', '+49 123 456798', 9, 'Legal Counsel', 75000.00, 1),
    ('Philipp', 'Vogel', 'philipp.vogel@example.com', '+49 123 456799', 10, 'Office Manager', 42000.00, 1),
    ('Nina', 'König', 'nina.koenig@example.com', '+49 123 456700', 11, 'Security Specialist', 56000.00, 1),
    ('Daniel', 'Graf', 'daniel.graf@example.com', '+49 123 456701', 12, 'System Engineer', 68000.00, 1),
    ('Hannah', 'Frank', 'hannah.frank@example.com', '+49 123 456702', 13, 'Marketing Specialist', 48000.00, 1),
    ('Tobias', 'Arnold', 'tobias.arnold@example.com', '+49 123 456703', 14, 'Product Owner', 72000.00, 1),
    ('Mia', 'Seidel', 'mia.seidel@example.com', '+49 123 456704', 15, 'Data Analyst', 61000.00, 1),
    ('Oliver', 'Brandt', 'oliver.brandt@example.com', '+49 123 456705', 16, 'Trainer', 46000.00, 1),
    ('Lea', 'Krüger', 'lea.krueger@example.com', '+49 123 456706', 17, 'Procurement Officer', 49000.00, 1),
    ('Johannes', 'Peters', 'johannes.peters@example.com', '+49 123 456707', 18, 'Logistics Coordinator', 47000.00, 1),
    ('Clara', 'Jansen', 'clara.jansen@example.com', '+49 123 456708', 19, 'Executive Assistant', 53000.00, 1);

INSERT INTO project (ProjectName, description, departmentId, startDate, endDate, status, budget) VALUES
    ('Website Redesign', 'Complete redesign of company website', 1, '2025-01-15', NULL, 'active', 50000.00),
    ('CRM Implementation', 'Implement new CRM system', 1, '2025-02-01', '2025-12-31', 'active', 120000.00),
    ('Recruitment Campaign', 'Hire 10 new developers', 2, '2025-03-01', NULL, 'on_hold', 15000.00),
    ('Sales Dashboard', 'BI dashboard for sales KPIs', 3, '2025-04-01', NULL, 'active', 30000.00),
    ('Yearly Audit', 'External financial audit', 4, '2025-01-10', '2025-03-31', 'completed', 20000.00),
    ('Warehouse Optimization', 'Optimize warehouse layout', 5, '2025-05-01', NULL, 'planned', 40000.00),
    ('Support Portal', 'New customer support portal', 6, '2025-02-15', NULL, 'active', 35000.00),
    ('Prototype X', 'R&D prototype for new product', 7, '2025-06-01', NULL, 'active', 80000.00),
    ('Test Automation', 'Automate regression tests', 8, '2025-03-15', NULL, 'active', 25000.00),
    ('Compliance Review', 'Review of new regulations', 9, '2025-04-15', NULL, 'active', 35000.00);

INSERT INTO skill (name, category, description) VALUES
    ('PHP', 'Programming', 'Object-oriented PHP for web applications'),
    ('MySQL', 'Database', 'Relational database design and SQL queries'),
    ('MariaDB', 'Database', 'Fork of MySQL used for web applications'),
    ('Docker', 'DevOps', 'Containerization for reproducible development environments'),
    ('Docker Compose', 'DevOps', 'Orchestration of multi-container setups'),
    ('Git', 'Tools', 'Version control and branching strategies'),
    ('GitHub', 'Tools', 'Remote repository hosting and collaboration'),
    ('HTML', 'Frontend', 'Structure of web pages'),
    ('CSS', 'Frontend', 'Styling and layout of web pages'),
    ('Twig', 'Templating', 'PHP templating engine for MVC views'),
    ('JavaScript', 'Frontend', 'Client-side scripting for interactive UIs'),
    ('REST API', 'Backend', 'Design and use of RESTful HTTP APIs'),
    ('Linux', 'Operating System', 'Server administration and shell usage'),
    ('Nginx', 'DevOps', 'Web server and reverse proxy'),
    ('Apache', 'DevOps', 'HTTP web server configuration'),
    ('OOP', 'Programming', 'Object-oriented design, classes and interfaces'),
    ('Design Patterns', 'Architecture', 'Reusable solutions like MVC, Repository'),
    ('Renewable Energy', 'Domain', 'Background in renewable energy engineering'),
    ('Scuba Diving', 'Personal', 'PADI-certified scuba diving experience'),
    ('Hiking', 'Personal', 'Long-distance hiking and outdoor activities');

INSERT INTO employeeproject (employeeId, projectId, role, hoursAllocated, assignedDate) VALUES
    (1, 1, 'Lead PHP Developer', 180, '2025-01-15'),
    (1, 2, 'Backend Developer', 120, '2025-02-01'),
    (2, 1, 'Frontend Developer', 160, '2025-01-20'),
    (2, 4, 'React Developer', 140, '2025-04-05'),
    (3, 3, 'HR Project Lead', 100, '2025-03-01'),
    (4, 4, 'Sales Analyst', 120, '2025-04-10'),
    (5, 5, 'Finance Specialist', 110, '2025-01-12'),
    (6, 6, 'Operations Coordinator', 130, '2025-05-03'),
    (7, 7, 'Support Agent', 150, '2025-02-18'),
    (8, 8, 'R&D Engineer', 200, '2025-06-02'),
    (9, 9, 'QA Engineer', 160, '2025-03-18'),
    (10, 10, 'Legal Advisor', 90, '2025-04-22'),
    (11, 1, 'Content Editor', 60, '2025-01-25'),
    (12, 1, 'Security Engineer', 140, '2025-02-20'),
    (13, 2, 'System Engineer', 170, '2025-02-25'),
    (14, 3, 'Marketing Manager', 150, '2025-03-05'),
    (15, 4, 'Product Owner', 180, '2025-03-10'),
    (16, 5, 'Data Analyst', 160, '2025-03-15'),
    (17, 6, 'Trainer', 120, '2025-03-20'),
    (18, 7, 'Procurement Officer', 130, '2025-03-25');

ALTER TABLE employee
    ADD CONSTRAINT fk_employee_department
    FOREIGN KEY (departmentId)
    REFERENCES department(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

ALTER TABLE department
    ADD CONSTRAINT fk_department_manager
    FOREIGN KEY (managerId)
    REFERENCES employee(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;


UPDATE department SET managerId = 1  WHERE id = 1;
UPDATE department SET managerId = 2  WHERE id = 2;
UPDATE department SET managerId = 3  WHERE id = 3;
UPDATE department SET managerId = 4  WHERE id = 4;
UPDATE department SET managerId = 5  WHERE id = 5;
UPDATE department SET managerId = 6  WHERE id = 6;
UPDATE department SET managerId = 7  WHERE id = 7;
UPDATE department SET managerId = 8  WHERE id = 8;
UPDATE department SET managerId = 9  WHERE id = 9;
UPDATE department SET managerId = 10 WHERE id = 10;
UPDATE department SET managerId = 11 WHERE id = 11;
UPDATE department SET managerId = 12 WHERE id = 12;
UPDATE department SET managerId = 13 WHERE id = 13;
UPDATE department SET managerId = 14 WHERE id = 14;
UPDATE department SET managerId = 15 WHERE id = 15;
UPDATE department SET managerId = 16 WHERE id = 16;
UPDATE department SET managerId = 17 WHERE id = 17;
UPDATE department SET managerId = 18 WHERE id = 18;
UPDATE department SET managerId = 19 WHERE id = 19;
UPDATE department SET managerId = 20 WHERE id = 20;


