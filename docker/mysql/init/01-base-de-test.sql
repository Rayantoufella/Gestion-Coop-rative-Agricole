-- Exécuté une seule fois, à la création du volume MySQL.
-- Base séparée pour PHPUnit : les tests vident leur base, pas celle de la démo.
CREATE DATABASE IF NOT EXISTS testing;
GRANT ALL PRIVILEGES ON testing.* TO 'gca_user'@'%';
FLUSH PRIVILEGES;
