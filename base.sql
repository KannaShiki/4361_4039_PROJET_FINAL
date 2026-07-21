-- Base de donnees pour le systeme d'operateur Mobile Money
-- Livraison v2

-- Suppression des tables existantes si elles existent
DROP TABLE IF EXISTS multi_send_recipients;
DROP TABLE IF EXISTS operator_commissions;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS fee_brackets;
DROP TABLE IF EXISTS operation_types;
DROP TABLE IF EXISTS operator_prefixes;
DROP TABLE IF EXISTS other_operator_prefixes;

-- Table des prefixes autorises de l'operateur
CREATE TABLE operator_prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix VARCHAR(3) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des prefixes d'autres operateurs
CREATE TABLE other_operator_prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix VARCHAR(3) NOT NULL UNIQUE,
    operator_name VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des types d'operations
CREATE TABLE operation_types (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des baremes de frais par tranche de montant
CREATE TABLE fee_brackets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operation_type_id INTEGER NOT NULL,
    min_amount DECIMAL(10,2) NOT NULL,
    max_amount DECIMAL(10,2) NOT NULL,
    fee_amount DECIMAL(10,2) NOT NULL,
    fee_percentage DECIMAL(5,2) DEFAULT 0,
    is_other_operator BOOLEAN DEFAULT 0,
    promotion_percentage DECIMAL(5,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operation_type_id) REFERENCES operation_types(id)
);

-- Table des clients
CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    phone_number VARCHAR(20) NOT NULL UNIQUE,
    balance DECIMAL(10,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des transactions (historique des operations)
CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    operation_type_id INTEGER NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    fee DECIMAL(10,2) DEFAULT 0,
    balance_before DECIMAL(10,2) NOT NULL,
    balance_after DECIMAL(10,2) NOT NULL,
    recipient_phone VARCHAR(20),
    description TEXT,
    include_withdrawal_fee BOOLEAN DEFAULT 0,
    is_multi_send BOOLEAN DEFAULT 0,
    operator_id INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (operation_type_id) REFERENCES operation_types(id)
);

-- Table des commissions inter-operateurs
CREATE TABLE operator_commissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operator_prefix_id INTEGER NOT NULL,
    commission_percentage DECIMAL(5,2) DEFAULT 0,
    commission_amount DECIMAL(10,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operator_prefix_id) REFERENCES other_operator_prefixes(id)
);

-- Table des destinataires multiples pour les multi-envois
CREATE TABLE multi_send_recipients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER NOT NULL,
    recipient_phone VARCHAR(20) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    fee DECIMAL(10,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id)
);

-- Insertion des donnees initiales

-- Prefixes autorises
INSERT INTO operator_prefixes (prefix) VALUES ('033');
INSERT INTO operator_prefixes (prefix) VALUES ('037');
INSERT INTO operator_prefixes (prefix) VALUES ('034');
INSERT INTO operator_prefixes (prefix) VALUES ('038');

-- Prefixes d'autres operateurs
INSERT INTO other_operator_prefixes (prefix, operator_name) VALUES ('032', 'Orange');
INSERT INTO other_operator_prefixes (prefix, operator_name) VALUES ('031', 'Airtel');
INSERT INTO other_operator_prefixes (prefix, operator_name) VALUES ('038', 'Telma');

-- Types d'operations
INSERT INTO operation_types (code, name, description) VALUES ('depot', 'Depot', 'Ajout d''argent sur le compte');
INSERT INTO operation_types (code, name, description) VALUES ('retrait', 'Retrait', 'Retrait d''argent du compte avec frais');
INSERT INTO operation_types (code, name, description) VALUES ('transfert', 'Transfert', 'Transfert d''argent vers un autre compte avec frais');

-- Baremes de frais pour les retraits
-- 0 - 10 000 Ar : 100 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (2, 0, 10000, 100, 0);
-- 10 001 - 50 000 Ar : 200 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (2, 10000.01, 50000, 200, 0);
-- 50 001 - 100 000 Ar : 400 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (2, 50000.01, 100000, 400, 0);
-- 100 001 - 500 000 Ar : 800 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (2, 100000.01, 500000, 800, 0);
-- Plus de 500 000 Ar : 1 500 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (2, 500000.01, 999999999, 1500, 0);

-- Baremes de frais pour les transferts
-- 0 - 10 000 Ar : 50 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (3, 0, 10000, 50, 0);
-- 10 001 - 50 000 Ar : 100 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (3, 10000.01, 50000, 100, 0);
-- 50 001 - 100 000 Ar : 200 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (3, 50000.01, 100000, 200, 0);
-- 100 001 - 500 000 Ar : 400 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (3, 100000.01, 500000, 400, 0);
-- Plus de 500 000 Ar : 800 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (3, 500000.01, 999999999, 800, 0);

-- Les depots sont gratuits (pas de frais)
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) 
VALUES (1, 0, 999999999, 0, 0);

-- Baremes de frais pour les transferts vers autres operateurs (frais supplementaires)
-- 0 - 10 000 Ar : 100 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage, is_other_operator) 
VALUES (3, 0, 10000, 100, 0, 1);
-- 10 001 - 50 000 Ar : 200 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage, is_other_operator) 
VALUES (3, 10000.01, 50000, 200, 0, 1);
-- 50 001 - 100 000 Ar : 400 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage, is_other_operator) 
VALUES (3, 50000.01, 100000, 400, 0, 1);
-- 100 001 - 500 000 Ar : 800 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage, is_other_operator) 
VALUES (3, 100000.01, 500000, 800, 0, 1);
-- Plus de 500 000 Ar : 1 500 Ar
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage, is_other_operator) 
VALUES (3, 500000.01, 999999999, 1500, 0, 1);

-- Commissions inter-operateurs
INSERT INTO operator_commissions (operator_prefix_id, commission_percentage, commission_amount) 
VALUES (1, 5, 0); -- Telma: 5%
INSERT INTO operator_commissions (operator_prefix_id, commission_percentage, commission_amount) 
VALUES (2, 3, 0); -- Orange: 3%
INSERT INTO operator_commissions (operator_prefix_id, commission_percentage, commission_amount) 
VALUES (3, 4, 0); -- Airtel: 4%

ALTER TABLE fee_brackets ADD COLUMN promotion_percentage DECIMAL(5,2) DEFAULT 0;