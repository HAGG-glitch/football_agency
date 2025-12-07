CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin','Player','Agent','Club Manager') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Sample Data
INSERT INTO users (name, email, password, role) VALUES
-- Admins
('John Admin', 'admin1@example.com', 'adminpass1', 'Admin'),
('Sarah Admin', 'admin2@example.com', 'adminpass2', 'Admin'),

-- Players
('Michael Striker', 'player1@example.com', 'playerpass1', 'Player'),
('Samuel Midfielder', 'player2@example.com', 'playerpass2', 'Player'),

-- Agents
('Alex Agent', 'agent1@example.com', 'agentpass1', 'Agent'),
('Rico Agent', 'agent2@example.com', 'agentpass2', 'Agent'),

-- Club Managers
('Chris Manager', 'manager1@example.com', 'managerpass1', 'Club Manager'),
('Daniel Manager', 'manager2@example.com', 'managerpass2', 'Club Manager');
